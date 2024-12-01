<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index($course_id)
    {

        $data['forums'] = Forum::with('comment.user')->where('course_id', $course_id)->get();

        // Check if there are any forums
        if ($data['forums']->isEmpty()) {
            $data['activeForum'] = null; // No forums available
        } else {
            $data['activeForum'] = Forum::where('course_id', $course_id)
                ->when(empty($request->id), function ($query) {
                    return $query->first();
                }, function ($query) use ($course_id) {
                    return $query->find($course_id);
                });

            // If the active forum was not found, set it to null
            if (!$data['activeForum']) {
                $data['activeForum'] = $data['forums']->first(); // Fallback to the first forum if no active forum is found
            }
        }
        return response()->json($data, 200);



    }
}
