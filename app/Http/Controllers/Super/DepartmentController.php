<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $department = Department::all();

            return datatables($department)
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
                    <button onclick="getEditModal(\'' . route('superAdmin.department.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('superAdmin.department.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
        }
        $data['activeDepartment'] = 'active';
        return view('super.department.index', $data);
    }

    public function store(Request $request)
    {
        Department::create(['name' => $request->name]);
        return redirect()->route('superAdmin.department.index')->with('success', 'Department created Successfully');
    }

    public function updateStatus(Request $request)
    {

        Department::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data['department'] = Department::where('id', $id)->first();
        return view('super.department.edit-form', $data);
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if admin has associated courses
        $department = Department::where('id', $id)->first();


        // Update admin details
        $department->name = $request->name;
        $department->save();

        return redirect()->route('superAdmin.department.index')->with('success', 'Department updated successfully');
    }

    public function delete(Request $request, $id)
    {
        // Find the semester
        $semester = Department::find($id);
        // Check if the semester has associated courses
        $hasCourses = Course::where('department_id', $id)->exists();


        if ($hasCourses) {
            return response()->json(['error' => 'Cannot delete department as it has associated courses.']);
        }

        // Delete the semester
        $semester->delete();

        return response()->json(['success' => true]);
    }
}
