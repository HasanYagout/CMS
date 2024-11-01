<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Instructor;
use App\Models\InstructorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $availability = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        $data['courses'] = $availability->map(function ($availability) {
            return $availability->course;
        });

        return view('instructor.courses.lectures.activities.index',$data);
    }

    public function store(Request $request)
    {
        foreach ($request->activity_title as $index => $title) {
            InstructorActivity::create([
                'lecture_id' => $request->lecture_id,
                'title' => $title,
                'options' => $request->options[$index],
                'correct_answer' => $request->correct_answer[$index],
                'grade' => $request->grade[$index],
            ]);
        }

    }
}
