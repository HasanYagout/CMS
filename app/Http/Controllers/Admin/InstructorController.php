<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Availabilities;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\InstructorActivity;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
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
            $admins = User::where('role_id', 2)
                ->whereHas('instructor', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->with('admin.department', 'instructor')
                ->get();

            return datatables($admins)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->instructor->first_name.' '.$data->instructor->last_name;

                })
                ->addColumn('email', function ($data) {
                    return $data->email;

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
                    <button onclick="getEditModal(\'' . route('admin.instructors.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('admin.instructors.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="'.__('Delete').'">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
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


        return redirect()->route('admin.instructors.index')->with('success', 'admin added successfully.');
    }
    public function updateStatus(Request $request)
    {

        User::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data['instructor']= Instructor::where('user_id', $id)->first();
        return view('admin.instructors.edit-form', $data);
    }
    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        // Check if admin has associated courses
        $instructor = Instructor::where('user_id',$id)->first();


        // Update admin details
        $instructor->first_name = $request->first_name;
        $instructor->last_name = $request->last_name;
        $instructor->save();

        return redirect()->route('admin.instructors.index')->with('success','Instructor updated successfully');
    }

    public function delete(Request $request, $id)
    {
        // Check if the instructor has associated courses
        $hasCourses = Availabilities::where('instructor_id', $id)->exists();

        // Check if the instructor has associated assignments, quizzes, or activities
        $hasAssignments = InstructorAssignments::where('instructor_id', $id)->exists();
        $hasQuizzes = InstructorQuiz::where('instructor_id', $id)->exists();
        $hasActivities = InstructorActivity::where('instructor_id', $id)->exists();

        if ($hasCourses || $hasAssignments || $hasQuizzes || $hasActivities) {
            return back()->with('error', 'Cannot delete instructor as there are associated courses, assignments, quizzes, or activities.');
        }
        User::find($id)->delete();
        Instructor::where('user_id',$id)->delete();
        return redirect()->route('admin.instructors.index')->with('success', 'Instructor deleted successfully.');

    }
}
