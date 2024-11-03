<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $_GET['course_id'] ?? null;
        $data['forums'] = Forum::where('course_id', $courseId)->get();

        $data['activeForum'] = Forum::where('course_id', $courseId)
            ->when(empty($request->id), function ($query) {
                return $query->first();
            }, function ($query) use ($request) {
                return $query->find($request->id);
            });

        return view('student.courses.forum.index',$data);

    }
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'message' => 'required|string',
        ]);

        Chat::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back();
    }
}
