<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lecture;
use Exception;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index($course_id)
    {
        try {
            // Fetch lectures related to the course with materials
            $lectures = Lecture::whereHas('chapters.course', function ($query) use ($course_id) {
                $query->where('id', $course_id);
            })
                ->with('materials')
                ->get();

            // Check if any lectures are found
            if ($lectures->isEmpty()) {
                return response()->json(['message' => 'No lectures found for this course.'], 404);
            }

            // Prepare the data array
            $data['lectures'] = $lectures;

            // Return the data as a JSON response
            return response()->json($data, 200);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
