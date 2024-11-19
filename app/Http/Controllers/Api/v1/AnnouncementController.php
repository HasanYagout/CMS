<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Exception;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index($courseId)
    {
        try {
            // Fetch announcements for the given course, ordered by creation date
            $announcements = Announcement::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if announcements are found
            if ($announcements->isEmpty()) {
                return response()->json(['message' => 'No announcements found for this course.'], 404);
            }

            // Prepare the data array
            $data['Announcements'] = $announcements;

            // Return the data as a JSON response
            return response()->json($data, 200);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
