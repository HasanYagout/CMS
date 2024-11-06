<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Availabilities;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Contracts\DataTable;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $announcement = Announcement::with('course')->where('instructor_id', Auth::id())->get();

            return datatables($announcement)
                ->addIndexColumn()
                ->addColumn('course', function ($data) {

                    return $data->course->name;
                })
                ->addColumn('type', function ($data) {
                    if ($data->text_title) {
                        return 'announcement';
                    } else {
                        return 'vote';
                    }
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
                    <button onclick="getEditModal(\'' . route('instructor.courses.announcement.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.announcement.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['course', 'type', 'action', 'status'])
                ->make(true);
        }

        $data['courses'] = Availabilities::where('instructor_id', Auth::id())
            ->with('course')
            ->get()
            ->map(function ($availability) {
                return [
                    'id' => $availability->course->id,
                    'name' => $availability->course->name,
                ];
            })
            ->toArray();
        $data['activeCourseAnnouncement'] = 'active';
        $data['showCourseManagement'] = 'show';
        return view('instructor.courses.announcement.index', $data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'announcement_type' => 'required|in:vote,text',
            'vote_title' => 'required_if:announcement_type,vote',
            'choices' => 'exclude_if:announcement_type,text|required_if:announcement_type,vote|array|min:2', // Exclude if text, validate if vote
            'choices.*' => 'exclude_if:announcement_type,text|required_if:announcement_type,vote|string',
            'text_title' => 'required_if:announcement_type,text',
            'announcement_text' => 'required_if:announcement_type,text',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store the announcement
        $announcement = new Announcement();
        $announcement->course_id = $request->course_id;
        $announcement->announcement_type = $request->announcement_type;
        $announcement->instructor_id = Auth::id();
        $announcement->status = 1;

        if ($request->announcement_type === 'vote') {
            $announcement->title = $request->vote_title;
            $choices = [];
            foreach ($request->choices as $choice) {
                $choices[] = [
                    'name' => $choice,
                    'count' => 0
                ];
            }
            $announcement->choices = json_encode($choices);

        } elseif ($request->announcement_type === 'text') {
            $announcement->title = $request->text_title;
            $announcement->text = $request->announcement_text;
        }

        $announcement->save();

        return redirect()->route('instructor.courses.announcement.index')->with('success', 'Announcement created successfully.');
    }

    public function edit($id)
    {

        $data['announcement'] = Announcement::where('id', $id)->first();
        return view('instructor.courses.announcement.edit-form', $data);
    }


    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'announcement_type' => 'required|in:text,vote',
            'vote_title' => 'required_if:announcement_type,vote|string|max:255',
            'choices' => 'required_if:announcement_type,vote|array',
            'choices.*' => 'required_if:announcement_type,vote|string|max:255',
            'text_title' => 'required_if:announcement_type,text|string|max:255',
            'announcement_text' => 'required_if:announcement_type,text|string',
        ]);

        // Find the announcement
        $announcement = Announcement::findOrFail($id);

        // Update announcement details
        $announcement->announcement_type = $request->announcement_type;
        $announcement->title = $request->announcement_type == 'vote' ? $request->vote_title : $request->text_title;
        $announcement->text = $request->announcement_type == 'text' ? $request->announcement_text : null;
        if ($request->announcement_type == 'vote') {
            $choices = collect(json_decode($announcement->choices))->map(function ($choice, $index) use ($request) {
                return ['name' => $request->choices[$index], 'count' => $choice->count];
            });
            $announcement->choices = json_encode($choices);
        } else {
            $announcement->choices = null;
        }

        $announcement->save();


        return redirect()->route('instructor.courses.announcement.index')->with('success', 'Announcement Updated successfully.');

    }


    public function updateStatus(Request $request)
    {
        Announcement::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Announcement status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $announcement = Announcement::find($id);
        $announcement->delete();
        return response()->json(['message' => 'Announcement deleted successfully.']);
    }
}
