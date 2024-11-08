<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->course_id) {
                $enrollments = Enrollment::with('student', 'course.chapters.lectures')
                    ->where('course_id', $request->course_id)
                    ->get();

                $students = $enrollments->map(function ($enrollment) {
                    $lectures = $enrollment->course->chapters->flatMap(function ($chapter) {
                        return $chapter->lectures->map(function ($lecture) {
                            return ['id' => $lecture->id, 'name' => $lecture->title];
                        });
                    });


                    return [
                        'id' => $enrollment->student->user_id,
                        'first_name' => $enrollment->student->first_name,
                        'last_name' => $enrollment->student->last_name,
                        'lectures' => $lectures->toArray(),
                    ];
                });

                return response()->json(['data' => $students]);


            }
        }

        $data['courses'] = Availabilities::where('instructor_id', Auth::id())
            ->with('course')
            ->get()
            ->map(function ($availability) {
                return [
                    'id' => $availability->course->id,
                    'name' => $availability->course->name,
                ];
            })
            ->toArray();

        return view('instructor.courses.attendance.index', $data);
    }

    public function update(Request $request)
    {
        dd($request->all());
    }
}
