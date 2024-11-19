<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function index($slug)
    {
        try {
            // Fetch chapters related to the course using the course slug
            $chapters = Chapter::whereHas('course', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })->get();

            // Check if any chapters are found
            if ($chapters->isEmpty()) {
                return response()->json(['message' => 'No chapters found for this course.'], 404);
            }

            // Prepare the data array
            $data['chapters'] = $chapters;

            // Return the data as a JSON response
            return response()->json($data, 200);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
