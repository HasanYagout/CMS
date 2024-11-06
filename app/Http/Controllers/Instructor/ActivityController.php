<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Instructor;
use App\Models\InstructorActivity;
use App\Models\StudentActivity;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $activities = InstructorActivity::with('lecture.chapter.course')->where('instructor_id', Auth::id())->get();
            return datatables($activities)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('course', function ($data) {

                    return $data->lecture->chapter->course->name;
                })
                ->addColumn('chapter', function ($data) {

                    return $data->lecture->chapter->title;
                })
                ->addColumn('lecture', function ($data) {

                    return $data->lecture->title;
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
                    <button onclick="getEditModal(\'' . route('instructor.courses.lectures.activities.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.lectures.activities.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
        }
        $availability = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        $data['courses'] = $availability->map(function ($availability) {
            return $availability->course;
        });
        $data['showCourseManagement'] = 'show';
        $data['activeCourseActivity'] = 'active';
        return view('instructor.courses.activities.index', $data);
    }


    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'lecture_id' => 'required|exists:lectures,id',
            'activity_title' => 'required|array',
            'activity_title.*' => 'required|string|max:255',
            'options' => 'required|array',
            'options.*' => 'required|string|max:255',
            'correct_answer' => 'required|array',
            'correct_answer.*' => 'required|string|max:255',
            'grade' => 'required|array',
            'grade.*' => 'required|numeric|min:0',
        ]);
        // Loop through activities and save each one
        foreach ($request->activity_title as $index => $title) {
            InstructorActivity::create([
                'lecture_id' => $request->lecture_id,
                'title' => $title,
                'due_date' => $request->due_date,
                'options' => json_encode(explode(',', $request->options[$index])),
                'correct_answer' => $request->correct_answer[$index],
                'grade' => $request->grade[$index],
                'instructor_id' => Auth::id(),
                'status' => 1
            ]);
        }


        return redirect()->back()->with('success', 'Activities saved successfully.');
    }

    public function edit($id)
    {

        $data['activity'] = InstructorActivity::where('id', $id)->first();
        return view('instructor.courses.activities.edit-form', $data);
    }


    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'options' => 'required|string',
            'correct_answer' => 'required|string|max:255',
            'grade' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        // Find the activity
        $activity = InstructorActivity::findOrFail($id);

        // Update activity details
        $activity->title = $request->title;
        $activity->options = json_encode(explode(',', $request->options));
        $activity->correct_answer = $request->correct_answer;
        $activity->grade = $request->grade;
        $activity->due_date = $request->due_date;
        $activity->save();

        return back()->with('success', 'Activity updated successfully.');
    }


    public function updateStatus(Request $request)
    {
        InstructorActivity::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Activity status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $activity = InstructorActivity::find($id);
        $hasActivity = StudentActivity::where('instructor_activity_id', $activity->id)->exists();
        if ($hasActivity) {
            return response()->json(['message' => 'Cannot delete activity as it has associated submitted activities.'], 400);
        }
        $activity->delete();
        return response()->json(['message' => 'Activity deleted successfully.']);
    }


}
