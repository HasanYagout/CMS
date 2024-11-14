<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\InstructorActivity;
use App\Models\InstructorQuiz;
use App\Models\Lecture;
use App\Models\StudentActivity;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LectureController extends Controller
{


    public function view($courseId, $lectureId)
    {
        $course = Course::with([
            'chapters.lectures.quizzes',
            'chapters.lectures.activities.studentActivity',
            'chapters.lectures.assignments.submittedAssignments',
            'chapters.lectures.quizzes.submittedQuiz',
        ])->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $activeLecture = $course->chapters->flatMap->lectures->firstWhere('id', $lectureId);
        $lectures = $course->chapters->flatMap->lectures;

        if (!$activeLecture) {
            return redirect()->back()->with('error', 'Lecture not found.');
        }

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $startDate = Carbon::parse($activeLecture->start_date);

        // Calculate the total number of lectures
        $totalLectures = $lectures->count();

        // Calculate the number of lectures attended by the student
        $studentId = Auth::id();
        $attendedLectures = Attendance::where('student_id', $studentId)
            ->whereIn('lecture_id', $lectures->pluck('id'))
            ->count();
        $totalQuizGrades = $activeLecture->quizzes->sum('grade');
        $studentTotalQuizGrades = $activeLecture->quizzes->map(function ($quiz) use ($studentId) {
            $studentQuiz = StudentQuiz::where('instructor_quiz_id', $quiz->id)
                ->where('student_id', $studentId)
                ->first();
            return $studentQuiz ? $studentQuiz->grade : 0;
        })
            ->sum();
        $quizzes = $activeLecture->quizzes->map(function ($quiz) use ($studentId) {
            $studentQuiz = StudentQuiz::where('instructor_quiz_id', $quiz->id)
                ->where('student_id', $studentId)->first();
            $quiz->alreadySubmitted = $studentQuiz ? true : false;
            $quiz->grade = $studentQuiz ? $studentQuiz->grade : 0;
            $quiz->grade_total = $quiz->grade;
            return $quiz;
        });

        $totalAssignmentGrade = $activeLecture->assignments->filter(fn($assignment) => $assignment->submittedAssignments->isNotEmpty())->sum(fn($assignment) => $assignment->submittedAssignments->first()->grade);

        $totalActivityGrade = $activeLecture->activities->filter(fn($activity) => $activity->studentActivity->isNotEmpty())->sum(fn($activity) => $activity->studentActivity->first()->grade);
        $data = [
            'course' => $course,
            'activeLecture' => $activeLecture,
            'lectures' => $lectures,
            'totalLectures' => $totalLectures,
            'attendedLectures' => $attendedLectures,
            'totalQuizzes' => $activeLecture->quizzes->count(),
            'totalAssignments' => $activeLecture->assignments->count(),
            'totalActivities' => $activeLecture->activities->count(),
            'submittedAssignments' => $activeLecture->assignments->filter(fn($assignment) => $assignment->submittedAssignments->isNotEmpty())->count(),
            'submittedActivities' => $activeLecture->activities->filter(fn($activity) => $activity->studentActivity->isNotEmpty())->count(),
            'submittedQuizzes' => $activeLecture->quizzes->filter(fn($quiz) => $quiz->submittedQuiz->isNotEmpty())->count(),
            'quizzes' => $activeLecture->quizzes,
            'totalAssignmentGrade' => $totalAssignmentGrade,
            'totalActivityGrade' => $totalActivityGrade,
            'total' => $totalQuizGrades + $totalAssignmentGrade + $totalActivityGrade,
            'totalQuizGrades' => $totalQuizGrades,
            'studentTotalQuizGrades' => $studentTotalQuizGrades,
        ];

        return view('student.courses.lectures.view', $data);
    }


    public function store(Request $request)
    {
        try {
            $grade = 0;
            foreach ($request->results as $result) {
                $activity = InstructorActivity::find($result['activity_id']);

                if ($result['selected_option'] == $activity->correct_answer) {
                    $grade = $activity->grade;
                }

                // Check if the student has already submitted the activity
                $existingActivity = StudentActivity::where('instructor_activity_id', $result['activity_id'])
                    ->where('student_id', Auth::id())
                    ->first();
                if (!$existingActivity) {
                    StudentActivity::create([
                        'instructor_activity_id' => $result['activity_id'],
                        'student_id' => Auth::id(),
                        'selected_option' => $result['selected_option'],
                        'correct' => $result['correct'] === 'true' ? 1 : 0,
                        'grade' => $grade
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => 'Activities submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while submitting activities.']);
        }
    }
}
