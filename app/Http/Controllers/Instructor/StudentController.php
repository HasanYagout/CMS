<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $enrollments = Enrollment::with('student', 'course', 'payment')->get();
            return datatables($enrollments)
                ->addIndexColumn()
                ->addColumn('course', function ($enrollment) {
                    return $enrollment->course->name;
                })
                ->addColumn('name', function ($enrollment) {
                    return $enrollment->student->first_name . ' ' . $enrollment->student->last_name;
                })
                ->addColumn('payment_status', function ($enrollment) {
                    $paid = $enrollment->payment->is_paid ? 'checked' : '';
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input disabled class="form-check-input toggle-status" type="checkbox" data-id="' . $enrollment->id . '" id="toggleStatus' . $enrollment->id . '" ' . $paid . '>
                        <label class="form-check-label" for="toggleStatus' . $enrollment->id . '"></label>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn('status', function ($enrollment) {
                    $status = $enrollment->status ? 'checked' : '';
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-status" type="checkbox" data-id="' . $enrollment->id . '" id="toggleStatus' . $enrollment->id . '" ' . $status . '>
                        <label class="form-check-label" for="toggleStatus' . $enrollment->id . '"></label>
                    </div>
                </li>
            </ul>';
                })
                ->rawColumns(['payment_status', 'status'])
                ->make(true);
        }
        $data['showCourseManagement'] = 'show';
        $data['activeCourseStudent'] = 'active';
        return view('instructor.courses.enrollment.index', $data);
    }

    public function updateStatus(Request $request)
    {
        Enrollment::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Enrollment status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $quiz = InstructorQuiz::find($id);
        $hasQuiz = StudentQuiz::where('instructor_quiz_id', $quiz->id)->exists();
        if ($hasQuiz) {
            return response()->json(['message' => 'Cannot delete quiz as it has associated submitted quizzes.'], 400);
        }

        $quiz->delete();
        return response()->json(['message' => 'Quiz deleted successfully.']);
    }
}
