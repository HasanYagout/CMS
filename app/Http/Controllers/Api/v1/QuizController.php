<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\InstructorQuiz;
use Exception;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index($courseId)
    {
        try {
            // Fetch quizzes related to the course with submitted quizzes
            $quizzes = InstructorQuiz::whereHas('lecture.chapters.course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            })
                ->with(['lecture.chapters.course', 'submittedQuiz'])
                ->get();

            // Check if any quizzes are found
            if ($quizzes->isEmpty()) {
                return response()->json(['message' => 'No quizzes found for this course.'], 404);
            }

            // Prepare the data array
            $data = ['quizzes' => $quizzes];

            // Return the data as a JSON response
            return response()->json($data, 200);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
