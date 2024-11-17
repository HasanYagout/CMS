<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
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
            ->with('lecture') // Eager load the lecture relationship
            ->whereBetween('due_date', [
                Carbon::now()->startOfDay(), // Start of today
                Carbon::now()->addDays(3)->endOfDay() // End of the third day from now
            ])
            ->get();

        // Fetch quizzes for the student that are due within the next 3 days
        $quizzes = InstructorQuiz::whereHas('lecture.chapters.course.enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with('course') // Eager load the course relationship
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

}
