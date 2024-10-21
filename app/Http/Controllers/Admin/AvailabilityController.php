<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Instructor;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $availability = Availabilities::with('instructor')->orderBy('id', 'desc')->get();

            return datatables($availability)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->instructor->first_name . ' ' . $data->instructor->last_name;
                })
                ->addColumn('days', function ($data) {
                    return json_decode($data->days);
                })
                ->addColumn('start_time', function ($data) {
                    return $data->start_time;
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
                ->rawColumns(['name','start_time','end_time','days','status','images'])
                ->make(true);
        }


        return view('admin.availability.index');

    }

    public function create()
    {
        $data['instructors'] = Instructor::all();
        return view('admin.availability.create',$data);
    }

    public function store(Request $request)
    {
        $availability=  $request->validate([
           'instructor_id' => 'required|exists:instructor,id',
           'days' => 'required',
           'start_time' => 'required',
           'end_time' => 'required',
        ]);
        $availability['days'] = json_encode($availability['days']);
        Availabilities::create($availability);
        session()->flash('success', 'Profile Updated Successfully');

        return back();
    }

    public function getAvailabilityByInstructor(Request $request)
    {
      return Availabilities::with('instructor')->where('instructor_id',$request->instructorId)->get();
    }
}
