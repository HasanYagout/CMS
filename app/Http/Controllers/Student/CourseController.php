<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $data['courses'] = Course::with('instructor')->paginate();
        return view('student.courses.index',$data);
    }
}
