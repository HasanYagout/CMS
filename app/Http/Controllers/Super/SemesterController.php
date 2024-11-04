<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index(Request $request)
    {
            if ($request->ajax()) {
                $semester=Semester::all();
                return datatables($semester)
                    ->addIndexColumn()
                    ->addColumn('name', function ($data) {
                        return $data->name;
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
                    <button onclick="getEditModal(\'' . route('superAdmin.semesters.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('superAdmin.semesters.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="'.__('Delete').'">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                    })
                    ->rawColumns(['name','action','status'])
                    ->make(true);
            }
            $data['activeSemester']='active';
        return view('super.semester.index',$data);
    }

    public function store(Request $request)
    {

        Semester::create(['name'=>$request->title]);

    }

    public function updateStatus(Request $request)
    {

        Semester::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data['semester']= Semester::where('id', $id)->first();
        return view('super.semester.edit-form', $data);
    }
    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if admin has associated courses
        $semester = Semester::where('id',$id)->first();


        // Update admin details
        $semester->name = $request->name;
        $semester->save();

        return redirect()->route('superAdmin.semesters.index')->with('success','Semester updated successfully');
    }

    public function delete(Request $request, $id)
    {
        // Find the semester
        $semester = Semester::find($id);
        // Check if the semester has associated courses
        $hasCourses = Course::where('semester_id', $id)->exists();


        if ($hasCourses) {
            return response()->json(['error' => 'Cannot delete semester as it has associated courses.']);
        }

        // Delete the semester
        $semester->delete();

        return redirect()->route('superAdmin.semesters.index')->with('success', 'Semester deleted successfully.');
    }

}
