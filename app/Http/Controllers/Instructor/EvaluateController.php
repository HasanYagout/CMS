<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class EvaluateController extends Controller
{
    public function edit($student_id)
    {
        $data['student'] = Student::where('user_id', $student_id)->first();

        return view('instructor.courses.evaluate.edit-form', $data);
    }
}
