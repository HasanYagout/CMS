<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\StudentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{

    public function view($courseId, $lectureId)
    {
        $course = Course::with([
            'chapters.lectures.quizzes',
            'chapters.lectures.activities.studentActivity',
            'chapters.lectures.assignments.submittedAssignments',
        ])->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $activeLecture = $course->chapters->flatMap->lectures->firstWhere('id', $lectureId);

        $lectures = $course->chapters->flatMap->lectures;

        if (!$activeLecture) {
            return redirect()->back()->with('error', 'Lecture not found.');
        }

        $data = [
            'course' => $course,
            'activeLecture' => $activeLecture,
            'lectures' => $lectures,
            'totalQuizzes' => $activeLecture->quizzes->count(),
            'totalAssignments' => $activeLecture->assignments->count(),
            'totalActivities' => $activeLecture->activities->count(),
            'submittedAssignments' => $activeLecture->assignments->filter(fn($assignment) => $assignment->submittedAssignments->isNotEmpty())->count(),
            'submittedActivities' => $activeLecture->activities->filter(fn($activity) => $activity->studentActivity->isNotEmpty())->count(),
            'quiz' => $activeLecture->quizzes->first(),
        ];

        return view('student.courses.lectures.view', $data);
    }


    public function store(Request $request)
    {
        try {


            foreach ($request->results as $result) {
                StudentActivity::create([
                    'instructor_activity_id' => $result['activity_id'],
                    'student_id' => Auth::id(),
                    'selected_option' => $result['selected_option'],
                    'correct' => $result['correct'] === 'true' ? 1 : 0,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Activities submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while submitting activities.']);
        }

    }
}
