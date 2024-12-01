<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Forum;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\Lecture;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function view($slug)
    {
        try {
            $course = Course::where('slug', $slug)->with(['availability.instructor'])->first();

            if (!$course) {
                return response()->json(['message' => 'Course not found.'], 404);
            }

            $studentId = Auth::id(); // Assuming the student is logged in

            // Get assignments for the logged-in student within the next 3 days
            $assignments = InstructorAssignments::with('lecture')
                ->whereHas('lecture.chapters.course', function ($query) use ($course) {
                    $query->where('id', $course->id);
                })
                ->whereIn('lecture_id', function ($query) use ($studentId) {
                    $query->select('lecture_id')
                        ->from('enrollment')
                        ->where('student_id', $studentId);
                })
                ->whereBetween('due_date', [
                    Carbon::now()->format('Y-m-d'),
                    Carbon::now()->addDays(3)->format('Y-m-d')
                ])
                ->get();

            // Get quizzes for the logged-in student within the next 3 days
            $quizzes = InstructorQuiz::with('lecture.chapter.course')
                ->whereHas('lecture.chapters.course', function ($query) use ($course) {
                    $query->where('id', $course->id);
                })
                ->whereIn('lecture_id', function ($query) use ($studentId) {
                    $query->select('lecture_id')
                        ->from('enrollment')
                        ->where('student_id', $studentId);
                })
                ->whereBetween('due_date', [
                    Carbon::now()->format('Y-m-d'),
                    Carbon::now()->addDays(3)->format('Y-m-d')
                ])
                ->get();

            $attentions = $assignments->merge($quizzes);
            $days = [];
            $hours = [];

            foreach ($attentions as $index => $attention) {
                $difference = Carbon::now()->diff($attention->due_date);
                $days[$index] = $difference->d;
                $hours[$index] = $difference->h;
            }

            // Get lectures within the next 3 days
            $lectures = Lecture::whereHas('chapter.course', function ($query) use ($course) {
                $query->where('id', $course->id);
            })
                ->whereBetween('start_date', [Carbon::today(), Carbon::today()->addDays(3)])
                ->with('chapter.course')
                ->get();

            // Get announcements for the course
            $announcements = Announcement::with('course')
                ->where('course_id', $course->id)
                ->whereIn('course_id', function ($query) use ($studentId) {
                    $query->select('course_id')
                        ->from('enrollment')
                        ->where('student_id', $studentId);
                })
                ->get();

            // Get forums for the course
            $forums = Forum::where('course_id', $course->id)->get();

            $data = [
                'course' => $course,
                'assignments' => $assignments,
                'quizzes' => $quizzes,
                'attentions' => $attentions,
                'days' => $days,
                'hours' => $hours,
                'lectures' => $lectures,
                'announcements' => $announcements,
                'forums' => $forums
            ];

            return response()->json($data, 200);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
