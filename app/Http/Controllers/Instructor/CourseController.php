<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $availabilities = Availabilities::with('instructor', 'course')->where('instructor_id', Auth::id())->get();
            dd($availabilities);
            return datatables($availabilities)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->course->name;
                })
                ->addColumn('image', function ($data) {
                    return '<img src="' . asset('storage/courses') . '/' . $data->course->image . '" alt="upload" />';
                })
                ->addColumn('lectures', function ($data) {
                    return $data->course->lectures;
                })
                ->addColumn('hours', function ($data) {
                    return $data->course->hours;
                })
                ->addColumn('days', function ($data) {
                    return json_decode($data->days);
                })
                ->addColumn('time', function ($data) {
                    return $data->start_time . ' - ' . $data->end_time;
                })
                ->rawColumns(['name', 'lectures', 'hours', 'time', 'days', 'image'])
                ->make(true);
        }
        $data['showCourseManagement'] = 'show';
        $data['activeCourseALL'] = 'active';

        return view('instructor.courses.index', $data);
    }


}

