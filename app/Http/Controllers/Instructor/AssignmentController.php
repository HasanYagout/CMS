<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\InstructorAssignments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()){
            $assignments = InstructorAssignments::with('course','chapters')->get();
            return datatables($assignments)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;

                })
                ->addColumn('course', function ($data) {
                    return $data->course->name;
                })
                ->addColumn('chapters', function ($data) {
                    return $data->chapter->title;
                })
                ->addColumn('end_time', function ($data) {
                    return $data->end_time;
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
                ->addColumn('images', function ($data) {
                    return '<button onclick="getEditModal(\'' . route('admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="' . __('Upload') . '">
                            <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="upload" />
                        </button>';
                })
                ->rawColumns(['name','course','chapters','days','status','images'])
                ->make(true);
        }
        $data['courses']=Availabilities::with('course')->where('instructor_id',Auth::id())->get();
        return view('instructor.courses.assignment.index',$data);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id', // Validate course existence
            'chapter_id' => 'required|integer|exists:chapters,id', // Validate chapters existence
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'marks' => 'required|integer',
            'due_date' => 'required|date',
        ]);
        InstructorAssignments::create($validated);

    }
}
