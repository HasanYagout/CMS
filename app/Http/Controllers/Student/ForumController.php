<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->query('course_id');
        $data['forums'] = Forum::with('comment.user')->where('course_id', $courseId)->get();

        // Check if there are any forums
        if ($data['forums']->isEmpty()) {
            $data['activeForum'] = null; // No forums available
        } else {
            $data['activeForum'] = Forum::where('course_id', $courseId)
                ->when(empty($request->id), function ($query) {
                    return $query->first();
                }, function ($query) use ($request) {
                    return $query->find($request->id);
                });

            // If the active forum was not found, set it to null
            if (!$data['activeForum']) {
                $data['activeForum'] = $data['forums']->first(); // Fallback to the first forum if no active forum is found
            }
        }

        $data['activeForumSide'] = 'active';
        return view('student.courses.forum.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'forum_id' => 'required|exists:forum,id',
            'message' => 'required|string',
        ]);

        Comment::create([
            'forum_id' => $request->forum_id,
            'student_id' => Auth::id(),
            'comment' => $request->message,
        ]);

        return back();
    }
}
