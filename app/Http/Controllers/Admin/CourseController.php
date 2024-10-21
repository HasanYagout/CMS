<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $course = Course::orderBy('id', 'desc')->get();

            return datatables($course)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
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
                ->addColumn('images', function ($data) {
                    return '<button onclick="getEditModal(\'' . route('admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="' . __('Upload') . '">
                            <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="upload" />
                        </button>';
                })
                ->rawColumns(['name','status','images'])
                ->make(true);
        }


        return view('admin.courses.index');
    }

    public function edit()
    {

    }

    public function create()
    {
        $data['availabilities']=Availabilities::with('instructor')->get();
        $data['instructors']=Instructor::all();
        $data['semesters']=Semester::all();
        return view('admin.courses.create',$data);
    }

    public function store(Request $request)
    {
      $course= $request->validate([
          'name' => 'required',
           'instructor_id' => 'required|exists:instructor,id',
           'availability_id' => 'required|exists:availabilities,id',
           'semester_id' => 'required|exists:academic_years,id',
           'description' => 'required',
       ]);
      $course['status']=1;
      $course['slug']=Str::slug($course['name']).'-'.Str::random(6);
      Course::create($course);
        session()->flash('success', 'Course Created Successfully');
        return back();

    }



}
