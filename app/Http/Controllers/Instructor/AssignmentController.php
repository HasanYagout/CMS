<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\InstructorAssignments;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $assignments = InstructorAssignments::with('lecture.chapter.course')->get();

            return datatables($assignments)
                ->addIndexColumn()
                ->addColumn('course', function ($data) {
                    return $data->lecture->chapter->course->name;
                })
                ->addColumn('chapter', function ($data) {
                    return $data->lecture->chapter->title;
                })
                ->addColumn('lecture', function ($data) {

                    return $data->lecture->title;
                })
                ->addColumn('title', function ($data) {

                    return $data->title;
                })
                ->addColumn('due_date', function ($data) {
                    return $data->due_date;
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status ? 'checked' : '';
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-status" type="checkbox" data-id="' . $data->id . '" id="toggleStatus' . $data->id . '" ' . $checked . '>
                        <label class="form-check-label" for="toggleStatus' . $data->id . '"></label>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <button onclick="getEditModal(\'' . route('instructor.courses.assignments.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.assignments.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'course', 'chapter', 'lecture', 'days', 'status', 'action'])
                ->make(true);
        }
        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        $data['showCourseManagement'] = 'show';
        $data['activeCourseAssignment'] = 'active';
        return view('instructor.courses.assignment.index', $data);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'lecture_id' => 'required|integer|exists:lectures,id',
            'chapter_id' => 'required|integer|exists:chapters,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'grade' => 'required|integer',
            'due_date' => 'required|date',
        ]);

        InstructorAssignments::create([
            'lecture_id' => $validated['lecture_id'],
            'chapter_id' => $validated['chapter_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'grade' => $validated['grade'],
            'due_date' => $validated['due_date'],
            'status' => 1,
            'instructor_id' => auth()->id() // Assuming you're using Laravel's authentication
        ]);

        return redirect()->route('instructor.courses.assignments.index')->with('success', __('Assignment Created Successfully'));
    }

    public function edit($id)
    {

        $data['assignment'] = InstructorAssignments::where('id', $id)->first();
        return view('instructor.courses.assignment.edit-form', $data);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'grade' => 'required|integer',
            'description' => 'required|string',
        ]);

        $assignment = InstructorAssignments::findOrFail($id);
        $assignment->update($validatedData);

        return redirect()->back()->with('success', 'Assignment updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        InstructorAssignments::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Assignment status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $assignment = InstructorAssignments::find($id);
        $hasAssignment = StudentAssignment::where('instructor_assignments_id', $assignment->id)->exists();
        if ($hasAssignment) {
            return response()->json(['message' => 'Cannot delete assignment as it has associated submitted assignments.'], 400);
        }
        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted successfully.']);
    }
}
