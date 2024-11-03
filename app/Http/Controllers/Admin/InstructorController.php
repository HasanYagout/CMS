<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departmentId = Auth::user()->admin->department_id;
            $admins = User::where('role_id', 1)
                ->orWhere('role_id', 2) // Assuming you also want to include Instructors
                ->whereHas('admin', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->with('admin.department', 'instructor')
                ->get();

            return datatables($admins)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->admin->first_name.' '.$data->admin->last_name;

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
                    return '<button onclick="getEditModal(\'' . route('admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="upload" />
            </button>';
                })
                ->rawColumns(['name','action','status'])
                ->make(true);
        }
        $data['activeInstructor']='active';
        return view('admin.instructors.index',$data);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);
        $departmentId = Auth::user()->admin->department_id;

       $user= User::create([
           'email' => $request->email,
           'password' => Hash::make('12345678'),
           'role_id' => 2,
           'status' => 1,
        ]);


           Instructor::create([
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
               'user_id' => $user->id,
               'department_id' => $departmentId,
           ]);


        return redirect()->route('admin.instructors.index')->with('success', 'Admin added successfully.');
    }
}
