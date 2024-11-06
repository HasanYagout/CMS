<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;

use App\Models\Semester;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $availability = Availabilities::with('instructor','course')->orderBy('id', 'desc')->get();
            return datatables($availability)
                ->addIndexColumn()
                ->addColumn('name', function ($data) {
                    return $data->course->name;
                })
                ->addColumn('instructor', function ($data) {
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
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <button onclick="getEditModal(\'' . route('admin.availability.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('admin.availability.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="'.__('Delete').'">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name','instructor','start_time','end_time','days','status','action'])
                ->make(true);
        }
        $data['courses']= Course::where('status',1)
            ->where('department_id',Auth::user()->admin->department_id)
            ->get();

        $data['instructors'] = User::where('role_id', 2)
            ->where('status', 1)
            ->whereHas('instructor', function ($query) {
                $query->where('department_id', Auth::user()->admin->department_id);
            })
            ->get();
        $data['showCourseManagement']='show';
        $data['activeCourseInstructor']='active';
        return view('admin.availability.index',$data);

    }

    public function create()
    {
        $data['instructors'] = Instructor::all();
        return view('admin.availability.create',$data);
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $availability = $request->validate([
            'instructor_id' => 'required|exists:instructor,user_id', // Ensure the correct table name
            'days' => 'required|array', // Validate that days is an array
            'days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', // Validate each day
            'start_time' => 'required|date_format:H:i', // Validate time format
            'end_time' => 'required|date_format:H:i|after:start_time', // Ensure end time is after start time
        ]);

        // Check for existing availabilities to prevent conflicts
        foreach ($availability['days'] as $day) {
            $conflict = Availabilities::where('instructor_id', $availability['instructor_id'])
                ->where('days', 'like', '%' . $day . '%')
                ->where(function ($query) use ($availability) {
                    $query->where(function ($q) use ($availability) {
                        $q->where('start_time', '<', $availability['end_time'])
                            ->where('end_time', '>', $availability['start_time']);
                    });
                })
                ->exists();

            if ($conflict) {
                return back()->withErrors(['days' => "The selected time conflicts with existing availability for $day."]);
            }
        }

        // Save the availability
        $availability['days'] = json_encode($availability['days']);
        $availability['course_id'] = $request->course_id;
        Availabilities::create($availability);

        session()->flash('success', 'Availability added successfully.');

        return back();
    }
    public function getInstructorAvailability($instructorId)
    {
        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Saturday', 'Sunday'];
        $allTimeSlots = [
            '08:00:00','09:00:00', '10:00:00', '11:00:00', '12:00:00',
            '13:00:00', '14:00:00', '15:00:00', '16:00:00'
        ];

        $existingAvailabilities = Availabilities::where('instructor_id', $instructorId)->get();

        $unavailableDays = [];
        foreach ($existingAvailabilities as $availability) {
            $days = json_decode($availability->days);
            foreach ($days as $day) {
                if (!isset($unavailableDays[$day])) {
                    $unavailableDays[$day] = [];
                }
                $unavailableDays[$day][] = [
                    'start_time' => $availability->start_time,
                    'end_time' => $availability->end_time,
                ];
            }
        }

        $availableDaysAndTimes = [];
        foreach ($allDays as $day) {
            if (!isset($unavailableDays[$day])) {
                $availableDaysAndTimes[$day] = $allTimeSlots;
            } else {
                $availableDaysAndTimes[$day] = array_filter($allTimeSlots, function ($time) use ($unavailableDays, $day) {
                    foreach ($unavailableDays[$day] as $unavailable) {
                        if ($time >= $unavailable['start_time'] && $time < $unavailable['end_time']) {
                            return false;
                        }
                    }
                    return true;
                });
            }
        }

        return response()->json($availableDaysAndTimes);
    }

    public function getAvailabilityByInstructor(Request $request)
    {
      return Availabilities::with('instructor')->where('instructor_id',$request->instructorId)->get();
    }
    public function updateStatus(Request $request)
    {
        Availabilities::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $data['availability']=Availabilities::with('instructor','course')->findOrFail($id);
        $data['courses']= Course::where('department_id',Auth::user()->admin->department_id)->get();

        $data['instructors']=Instructor::where('department_id',Auth::user()->admin->department_id)
            ->get();
        $data['semesters']=Semester::where('status',1)->get();
        return view('admin.availability.edit-form', $data);
    }
    public function update(Request $request, $id) {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $availability = Availabilities::find($id);
        $availability->instructor_id = $request->instructor_id;
        $availability->days = json_encode($request->days);
        $availability->start_time = $request->start_time;
        $availability->end_time = $request->end_time;
        $availability->course_id = $request->course_id;
        $availability->save();

        return redirect()->route('admin.availability.index')->with('success', 'Availability updated successfully.');
    }

    public function destroy($id)
    {

            $availability = Availabilities::find($id);
            $hasEnrollments = Enrollment::where('course_id', $availability->course_id)->exists();
            if ($hasEnrollments) {
                return response()->json(['message' => 'Cannot delete availability as there are associated records.'], 400);
            }

            $availability->delete();
        return response()->json(['message' => 'Availability deleted successfully.'], 200);

    }
}
