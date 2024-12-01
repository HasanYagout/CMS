<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\News;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class HomeController extends Controller
{
    public function notifications()
    {
        $studentId = Auth::id(); // Get the current logged-in student's ID

        // Fetch assignments for the student that are due within the next 3 days
        $assignments = InstructorAssignments::whereHas('lecture.chapters.course.enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with('lecture.chapter.course') // Eager load the lecture relationship
            ->whereBetween('due_date', [
                Carbon::now()->startOfDay(), // Start of today
                Carbon::now()->addDays(3)->endOfDay() // End of the third day from now
            ])
            ->get();

        // Fetch quizzes for the student that are due within the next 3 days
        $quizzes = InstructorQuiz::whereHas('lecture.chapters.course.enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with('lecture.chapter.course') // Eager load the course relationship
            ->whereBetween('due_date', [
                Carbon::now()->startOfDay(), // Start of today
                Carbon::now()->addDays(3)->endOfDay() // End of the third day from now
            ])
            ->get();

        // Prepare the response data
        $data = [
            'assignments' => $assignments,
            'quizzes' => $quizzes,
        ];

        // Return a JSON response
        return response()->json($data);
    }

    public function enrolled_courses()
    {
        $studentId = Auth::id();

        $courses = Course::whereHas('enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId)
                ->where('status', 1)
                ->whereHas('payment', function ($query) {
                    $query->where('is_paid', true);
                });
        })
            ->with(['availability.instructor']) // Eager load the instructor
            ->get();

        return response()->json($courses);
    }


    public function news(Request $request)
    {
        try {
            // Fetch the logged-in user's related student and department ID
            $user = Auth::user();

            // Ensure the user is associated with a student and has a department
            if (!$user || !$user->student || !$user->student->department_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student does not belong to a department'
                ], 403);
            }

            $departmentId = $user->student->department_id;

            // Fetch news based on the student's department ID
            $news = News::where('department_id', $departmentId)
                ->with('admin') // Load admin details for 'posted_by'
                ->get();

            // Map and prepare the response data
            $data = $news->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'posted_by' => $item->admin->first_name . ' ' . $item->admin->last_name,
                    'status' => $item->status ? 'Active' : 'Inactive',
                ];
            });

            // Return a JSON response with the news
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching news:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching news'
            ], 500);
        }
    }




}
