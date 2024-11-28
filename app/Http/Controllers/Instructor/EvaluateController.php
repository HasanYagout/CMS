<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Evaluate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluateController extends Controller
{
    public function edit($course_id, $student_id)
    {
        $data['student'] = Student::with('evaluate')->where('user_id', $student_id)->first();
        $data['course'] = $course_id;
        return view('instructor.courses.evaluate.edit-form', $data);
    }

    public function update(Request $request, $course_id, $student_id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);
        Evaluate::create([
            'student_id' => $student_id,
            'course_id' => $course_id,
            'instructor_id' => Auth::id(),
            'description' => $request->description,
        ]);
        return redirect()->back()->with('success', 'Evaluation created successfully');
    }
}
