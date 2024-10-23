<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // Load courses with instructors and chapters
        $courses = Course::with(['instructor', 'chapter.material','instructor.availability'])->get();

        // Calculate the total count of materials for each course
        foreach ($courses as $course) {
            $course->total_materials_count = $course->chapter->sum(function ($chapter) {
                return $chapter->material->count();
            });
        }

        return view('student.courses.index', ['courses' => $courses]);
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
