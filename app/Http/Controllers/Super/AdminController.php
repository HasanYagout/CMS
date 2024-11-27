<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $admins = User::where('role_id', 1)
                ->with('admin.department')
                ->get();

            return datatables($admins)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->admin->first_name . ' ' . $data->admin->last_name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('department', function ($data) {

                    return $data->admin->department->name;

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
                    <button onclick="getEditModal(\'' . route('superAdmin.admin.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('superAdmin.admin.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
        }
        $data['colleges'] = Department::all();

        $data['activeAdmin'] = 'active';
        return view('super.admin.index', $data);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department' => 'required|exists:department,id',
        ]);
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make('12345678'),
            'role_id' => 1,
            'status' => 1,
        ]);


        Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_id' => $user->id,
            'department_id' => $request->department,
        ]);


        return redirect()->route('superAdmin.admin.index')->with('success', 'admin added successfully.');
    }

    public function edit($id)
    {
        $data['admin'] = Admin::where('user_id', $id)->first();
        $data['departments'] = Department::all();
        return view('super.admin.edit-form', $data);
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department_id' => 'required|exists:department,id',
        ]);

        // Check if admin has associated courses
        $admin = Admin::where('user_id', $id)->first();

        $hasCourses = Availabilities::where('instructor_id', $admin->user_id)->exists();

        if ($hasCourses && $admin->department_id != $request->department_id) {
            return back()->with('error', 'Cannot change department as admin has associated courses.');
        }

        // Update admin details
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->department_id = $request->department_id;
        $admin->save();

        return redirect()->route('superAdmin.admin.index')->with('success', 'admin updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        User::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Admin not found.'], 404);
        }
        $hasCourses = Course::where('user_id', $id)->exists();

        if ($hasCourses) {
            return response()->json(['error' => 'Cannot delete admin as they have associated courses.']);
        }
        $user->delete();
        Admin::where('user_id', $id)->delete();
        return response()->json(['success' => 'Admin Deleted Successfully.']);

    }

}
