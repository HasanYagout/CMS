<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $data['courses']=Course::all();
        return view('instructor.courses.forum.index',$data);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Store the new forum
        Forum::create([
            'course_id' => $request->course_id,
            'instructor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('instructor.courses.forums.index')->with('success', 'Forum added successfully.');
    }
}
