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
        $data['availabilities'] = Availabilities::with(['course', 'instructor','course.semester', 'course.chapter.material'])
            ->get()
            ->map(function ($availability) {
                // Count materials for each course
                $availability->course->material_count = $availability->course->chapter->flatMap(function ($chapter) {

                    return $chapter->material;
                })->count();

                return $availability;
            });


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
        $data['chapters'] = Chapter::with('material')->where('course_id', $data['course']->id)->get();
        return view('student.courses.info', $data);
    }
}