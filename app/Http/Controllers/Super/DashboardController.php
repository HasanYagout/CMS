<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Availabilities;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
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
                        return $data->admin->first_name.' '.$data->admin->last_name;
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
                    return '<button onclick="getEditModal(\'' . route('superAdmin.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>';
                })
                ->rawColumns(['name','action','status'])
                ->make(true);
        }
        $data['colleges']=Department::all();
        $data['activeHome']='active';
        return view('super.dashboard',$data);
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
        $user= User::create([
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




        return redirect()->route('admin.instructors.index')->with('success', 'admin added successfully.');
    }

    public function edit($id)
    {
       $data['admin']= Admin::where('user_id', $id)->first();
       $data['departments']= Department::all();
        return view('super.edit-form', $data);
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
        $admin = Admin::where('user_id',$id)->first();

        $hasCourses = Availabilities::where('instructor_id', $admin->user_id)->exists();

        if ($hasCourses && $admin->department_id != $request->department_id) {
            return back()->with('error', 'Cannot change department as admin has associated courses.');
        }

        // Update admin details
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->department_id = $request->department_id;
        $admin->save();

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        User::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }
}
