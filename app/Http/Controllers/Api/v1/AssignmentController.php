<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\InstructorAssignments;
use App\Models\Lecture;
use Exception;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index($courseId)
    {
        try {
            // Fetch lectures related to the course with assignments and submitted assignments
            $lectures = Lecture::whereHas('chapters.course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
            })
                ->with(['chapters.course', 'assignments.submittedAssignments'])
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
