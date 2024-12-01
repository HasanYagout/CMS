<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\StudentQuiz;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lecture;


class LectureController extends Controller
{
    public function view($courseId, $lectureId)
    {
        try {
            // Load course data with related lectures, quizzes, activities, assignments
            $course = Course::with([
                'chapters.lectures.quizzes',
                'chapters.lectures.activities.studentActivity',
                'chapters.lectures.assignments.submittedAssignments',
                'chapters.lectures.quizzes.submittedQuiz',
            ])->find($courseId);

            if (!$course) {
                return response()->json(['error' => 'Course not found.'], 404);
            }

            // Find the active lecture
            $activeLecture = Lecture::whereHas('chapters.course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            })
                ->with('materials') // Eager load related materials for the lecture
                ->find($lectureId); // Fetch the lecture by its ID

            // Check if the lecture exists
            $lectures = $course->chapters->flatMap->lectures;

            if (!$activeLecture) {
                return response()->json(['error' => 'Lecture not found.'], 404);
            }

            // Check if the lecture is accessible today or tomorrow


            // Calculate total number of lectures

            // Calculate the number of lectures attended by the student
            $studentId = Auth::id();
            $attendedLectures = Attendance::where('student_id', $studentId)
                ->whereIn('lecture_id', $lectures->pluck('id'))
                ->count();

            // Calculate quiz grades
            $totalQuizGrades = $activeLecture->quizzes->sum('grade');
            $studentTotalQuizGrades = $activeLecture->quizzes->map(function ($quiz) use ($studentId) {
                $studentQuiz = StudentQuiz::where('instructor_quiz_id', $quiz->id)
                    ->where('student_id', $studentId)
                    ->first();
                return $studentQuiz ? $studentQuiz->grade : 0;
            })
                ->sum();


            // Calculate assignment and activity grades
            $totalAssignmentGrade = $activeLecture->assignments->filter(fn($assignment) => $assignment->submittedAssignments->isNotEmpty())->sum(fn($assignment) => $assignment->submittedAssignments->first()->grade);
            $totalActivityGrade = $activeLecture->activities->filter(fn($activity) => $activity->studentActivity->isNotEmpty())->sum(fn($activity) => $activity->studentActivity->first()->grade);

            // Prepare the data for the response
            $data = [
                'activeLecture' => $activeLecture,
                'attendedLectures' => $attendedLectures,
                'totalQuizzes' => $activeLecture->quizzes->count(),
                'totalAssignments' => $activeLecture->assignments->count(),
                'totalActivities' => $activeLecture->activities->count(),
                'submittedAssignments' => $activeLecture->assignments->filter(fn($assignment) => $assignment->submittedAssignments->isNotEmpty())->count(),
                'submittedActivities' => $activeLecture->activities->filter(fn($activity) => $activity->studentActivity->isNotEmpty())->count(),
                'submittedQuizzes' => $activeLecture->quizzes->filter(fn($quiz) => $quiz->submittedQuiz->isNotEmpty())->count(),
                'totalAssignmentGrade' => $totalAssignmentGrade,
                'totalActivityGrade' => $totalActivityGrade,
                'totalQuizGrades' => $totalQuizGrades,
                'studentTotalQuizGrades' => $studentTotalQuizGrades,
            ];

            return response()->json($data, 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
