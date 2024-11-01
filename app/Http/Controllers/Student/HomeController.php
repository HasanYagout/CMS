<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data['courses'] = Course::whereHas('availability')->with('availability')->get();

        return view('student.enrollment.index',$data);
    }
}
