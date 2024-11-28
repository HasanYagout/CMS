<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstructorActivity;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\Student;
use App\Models\StudentActivity;
use App\Models\StudentAssignment;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courses = Course::whereHas('availabilities', function ($query) {
                $query->where('instructor_id', Auth::id());
            })->with('enrollments', 'availabilities.instructor', 'semester')
                ->where('department_id', Auth::user()->instructor->department_id)
                ->get()
                ->map(function ($course) {
                    $course->enrollmentCount = $course->enrollments->count();
                    return $course;
                });

            return datatables($courses)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('instructor.reports.students', ['course_id' => $data->id]) . '">' . $data->name . '</a>';
                })
                ->addColumn('semester', function ($data) {
                    return $data->semester->name;
                })
                ->addColumn('students', function ($data) {
                    return $data->enrollmentCount;
                })
                ->addColumn('instructor', function ($data) {
                    if (!isset($data->availability->instructor)) {
                        return 'no Instructor';
                    } else {
                        return $data->availability->instructor->first_name . ' ' . $data->availability->instructor->last_name;
                    }
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status ? 'checked' : '';
                    return $checked ? 'active' : 'not active';
                })
                ->rawColumns(['name', 'instructor', 'days', 'status', 'images'])
                ->make(true);

        }
        $data['showReportManagement'] = 'show';
        $data['activeReport'] = 'active';
        return view('instructor.reports.courses', $data);
    }

    public function students(Request $request, $course_id)
    {
        if ($request->ajax()) {

            $assignments = InstructorAssignments::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $quizzes = InstructorQuiz::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $activities = InstructorActivity::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $attendance = Attendance::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $course = Course::with('chapters.lectures')
                ->where('id', $course_id)
                ->first();

            if ($course) {
                $totalLectures = $course->chapters->sum(function ($chapter) {
                    return $chapter->lectures->count();
                });
            }

            $attendanceSum = $totalLectures;
            // Fetch student submissions
            $submittedAssignments = StudentAssignment::whereHas('assignment.lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $submittedQuizzes = StudentQuiz::whereHas('instructorQuiz.lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $submittedActivities = StudentActivity::whereHas('InstructorActivity.lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            // Calculate total grade

            $assignmentGradesSum = $assignments->sum('grade');
            $quizGradesSum = $quizzes->sum('grade');
            $activityGradesSum = $activities->sum('grade');


            $totalGradesSum = $assignmentGradesSum + $quizGradesSum + $activityGradesSum;

            // Fetch students enrolled in the course
            $students = Enrollment::with('student', 'payment')->where('course_id', $course_id)->get();

            return datatables($students)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->student->first_name . ' ' . $data->student->last_name;
                })
                ->addColumn('assignments', function ($data) use ($submittedAssignments, $assignmentGradesSum) {
                    $studentAssignments = $submittedAssignments->where('student_id', $data->student_id);
                    $studentAssignmentMarks = $studentAssignments->sum('grade');
                    return $studentAssignmentMarks . '/' . $assignmentGradesSum;
                })
                ->addColumn('quizzes', function ($data) use ($submittedQuizzes, $quizGradesSum) {
                    $studentQuizzes = $submittedQuizzes->where('student_id', $data->student_id);
                    $studentQuizMarks = $studentQuizzes->sum('grade');
                    return $studentQuizMarks . '/' . $quizGradesSum;
                })
                ->addColumn('activities', function ($data) use ($submittedActivities, $activityGradesSum) {
                    $studentActivities = $submittedActivities->where('student_id', $data->student_id);
                    $studentActivityMarks = $studentActivities->sum('grade');
                    return $studentActivityMarks . '/' . $activityGradesSum;
                })
                ->addColumn('attendance', function ($data) use ($attendance, $attendanceSum) {
                    $studentAttendanceCount = count($attendance->where('student_id', $data->student_id));
                    return $studentAttendanceCount . '/' . $attendanceSum;
                })
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <button onclick="getEditModal(\'' . route('instructor.courses.evaluate.edit', ['course_id' => $data->course_id, 'student_id' => $data->student_id]) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }
        $data['showReportManagement'] = 'show';
        $data['activeReport'] = 'active';
        $data['course_id'] = $course_id;
        return view('instructor.reports.students', $data);
    }
}
