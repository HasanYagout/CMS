<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
                    ->paginate(10); // Adjust the number of items per page as needed

                $students = $enrollments->map(function ($enrollment) {
                    $lectures = $enrollment->course->chapters->flatMap(function ($chapter) use ($enrollment) {
                        return $chapter->lectures->map(function ($lecture) use ($enrollment) {
                            // Get the attendance status for this lecture and student
                            $attendance = Attendance::where('student_id', $enrollment->student->user_id)
                                ->where('lecture_id', $lecture->id)
                                ->where('status', 1)
                                ->first();

                            return [
                                'id' => $lecture->id,
                                'name' => $lecture->title,
                                'attended' => $attendance ? true : false // Check if attendance exists
                            ];
                        });
                    });

                    return [
                        'id' => $enrollment->student->user_id,
                        'first_name' => $enrollment->student->first_name,
                        'last_name' => $enrollment->student->last_name,
                        'lectures' => $lectures->toArray(),
                    ];
                });

                return response()->json([
                    'data' => $students,
                    'links' => (string)$enrollments->links() // Pass pagination links to the frontend
                ]);
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
        $data['activeCourseAttendance'] = 'active';
        $data['showCourseManagement'] = 'show';

        return view('instructor.courses.attendance.index', $data);
    }


    public function update(Request $request)
    {
        foreach ($request->attendanceData as $attendance) {
            Attendance::updateOrCreate(
                [
                    'lecture_id' => $attendance['lecture_id'],
                    'student_id' => $attendance['student_id']
                ],
                [
                    'status' => $attendance['status']
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Attendance updated successfully.']);
    }

}
