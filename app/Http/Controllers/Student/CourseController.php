<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $data['courses'] = Course::where('department_id', Auth::user()->student->department_id)->with('availability')->get();
        
        $data['activeCourseALL'] = 'active';
        $data['showCourseManagement'] = 'show';
        return view('student.courses.index', $data);
    }

    public function store(Request $request)
    {
        Auth::user();
    }

    public function info(Request $request, $slug)
    {

        $data['course'] = Course::where('slug', $slug)->first();
        $data['enrolled'] = Enrollment::where('course_id', $data['course']->id)
            ->where('student_id', Auth::id())
            ->exists();

        $startDate = Carbon::parse($data['course']->start_date);
        $today = Carbon::today();
        $data['daysLeft'] = $today->diffInDays($startDate, false);
        $data['available'] = 0;
        if ($data['daysLeft'] > 0) {
            $data['available'] = 1;
            $data['message'] = "There are {$data['daysLeft']} days left until the course begins.";
        } elseif ($data['daysLeft'] == 0) {
            $data['available'] = 1;
            $data['message'] = "The course starts today.";
        } else {
            $data['message'] = "The course has already started.";
        }


        $data['chapters'] = Chapter::with('lectures.materials')->where('course_id', $data['course']->id)->get();
        return view('student.courses.info', $data);
    }
}
