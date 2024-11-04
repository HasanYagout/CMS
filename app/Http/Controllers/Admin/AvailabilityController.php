<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Instructor;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                ->addColumn('action', function ($data) {
                    return '<button onclick="getEditModal(\'' . route('Admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo" title="' . __('Upload') . '">
                            <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
                        </button>';
                })
                ->rawColumns(['name','start_time','end_time','days','status','action'])
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

}
