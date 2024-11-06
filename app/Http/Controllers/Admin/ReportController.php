<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstructorActivity;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
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
            $courses = Course::with('enrollments', 'availability.instructor', 'semester')
                ->where('department_id', Auth::user()->admin->department_id)
                ->get()
                ->map(function ($course) {
                    $course->enrollmentCount = $course->enrollments->count();
                    return $course;
                });

            return datatables($courses)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return '<a href="' . route('admin.reports.students', ['course_id' => $data->id]) . '">' . $data->name . '</a>';
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
        return view('admin.reports.courses');
    }

    public function students(Request $request, $course_id)
    {

        if ($request->ajax()) {
            // Fetch instructor assignments, quizzes, and activities
            $assignments = InstructorAssignments::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $quizzes = InstructorQuiz::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

            $activities = InstructorActivity::whereHas('lecture.chapter.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })->get();

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
                    return '<a href="' . route('instructor.reports.grades', ['course_id' => $data->id]) . '">' . $data->student->first_name . ' ' . $data->student->last_name . '</a>';
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
                ->addColumn('total', function ($data) use ($submittedAssignments, $submittedQuizzes, $submittedActivities, $totalGradesSum) {
                    $studentAssignments = $submittedAssignments->where('student_id', $data->student_id)->sum('grade');
                    $studentQuizzes = $submittedQuizzes->where('student_id', $data->student_id)->sum('grade');
                    $studentActivities = $submittedActivities->where('student_id', $data->student_id)->sum('grade');
                    $studentTotal = $studentAssignments + $studentQuizzes + $studentActivities;
                    return $studentTotal . '/' . $totalGradesSum;
                })
                ->rawColumns(['name', 'status'])
                ->make(true);
        }

        $data['course_id'] = $course_id;
        $data['showReportsManagement'] = 'show';
        $data['activeReport'] = 'active';
        return view('admin.reports.students', $data);
    }
}
