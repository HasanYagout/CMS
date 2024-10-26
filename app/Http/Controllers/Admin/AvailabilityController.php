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
        // Define all possible days and time slots
        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $allTimeSlots = [
            '09:00:00', '10:00:00', '11:00:00', '12:00:00',
            '13:00:00', '14:00:00', '15:00:00', '16:00:00',
            '17:00:00', '18:00:00'
        ];

        // Fetch existing availabilities for the selected instructor
        $existingAvailabilities = Availabilities::where('instructor_id', $instructorId)->get();

        // Create an array to hold unavailable days and times
        $unavailableDays = [];
        foreach ($existingAvailabilities as $availability) {
            $days = json_decode($availability->days); // Assuming days are stored as a JSON array
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

        // Prepare available days and times
        $availableDaysAndTimes = [];
        foreach ($allDays as $day) {
            if (!isset($unavailableDays[$day])) {
                // If the day is not booked, add all time slots
                $availableDaysAndTimes[$day] = $allTimeSlots;
            } else {
                // If the day is booked, find available time slots
                $availableDaysAndTimes[$day] = array_filter($allTimeSlots, function ($time) use ($unavailableDays, $day) {
                    foreach ($unavailableDays[$day] as $unavailable) {
                        if ($time >= $unavailable['start_time'] && $time < $unavailable['end_time']) {
                            return false; // Time is unavailable
                        }
                    }
                    return true; // Time is available
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
