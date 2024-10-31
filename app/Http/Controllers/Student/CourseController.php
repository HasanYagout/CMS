<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $data['courses'] = Course::whereHas('availability')->with('availability')->get();


        $data['activeCourseALL']='active';
        $data['showCourseManagement']='show';
        return view('student.courses.index', $data);
    }

    public function store(Request $request)
    {
        Auth::user();

    }

    public function info(Request $request, $slug)
    {

        $data['course'] = Course::where('slug', $slug)->first();
        $data['chapters'] = Chapter::with('materials')->where('course_id', $data['course']->id)->get();
        return view('student.courses.info', $data);
    }
}
