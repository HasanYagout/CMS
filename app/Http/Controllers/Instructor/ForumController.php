<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $forum = Forum::with('course')->where('instructor_id', Auth::id())->get();
            return datatables($forum)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return '<a href="' . route('instructor.courses.forums.view', $data->id) . '">' . $data->title . '</a>';
                })
                ->addColumn('course', function ($data) {

                    return $data->course->name;
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
                    <button onclick="getEditModal(\'' . route('instructor.courses.forums.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.forums.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status', 'title'])
                ->make(true);
        }
        $data['courses'] = Course::whereHas('availability', function ($query) {
            $query->where('instructor_id', Auth::id());
        })->where('department_id', Auth::user()->instructor->department_id)->get();

        $data['showCourseManagement'] = 'show';
        $data['activeCourseForum'] = 'active';
        return view('instructor.courses.forum.index', $data);
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Store the new forum
        Forum::create([
            'course_id' => $request->course_id,
            'instructor_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('instructor.courses.forums.index')->with('success', 'Forum added successfully.');
    }

    public function edit($id)
    {

        $data['forum'] = Forum::where('id', $id)->first();
        $data['availabilities'] = Availabilities::where('instructor_id', Auth::id())->with('course')->get();
        $data['courses'] = $data['availabilities']->pluck('course')->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
            ];
        })->toArray();

        return view('instructor.courses.forum.edit-form', $data);
    }


    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'description' => 'required|string',
        ]);

        // Find the forum
        $forum = Forum::findOrFail($id);

        // Update forum details
        $forum->title = $request->title;
        $forum->course_id = $request->course_id;
        $forum->description = $request->description;
        $forum->save();

        return back()->with('success', 'Forum updated successfully.');
    }


    public function updateStatus(Request $request)
    {
        Forum::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Activity status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $forum = Forum::find($id);
        $hasComment = Comment::where('forum_id', $forum->id)->exists();
        if ($hasComment) {
            return response()->json(['message' => 'Cannot delete forum as it has associated submitted comments.'], 400);
        }
        $forum->delete();
        return response()->json(['message' => 'Forum deleted successfully.']);
    }

    public function view($id)
    {

        $data['forums'] = Forum::with('comment.user')->where('instructor_id', Auth::id())->get();

        // Check if there are any forums
        if ($data['forums']->isEmpty()) {
            $data['activeForum'] = null; // No forums available
        } else {
            $data['activeForum'] = Forum::where('instructor_id', Auth::id())
                ->when(empty($request->id), function ($query) {
                    return $query->first();
                }, function ($query) use ($id) {
                    return $query->find($id);
                });

            // If the active forum was not found, set it to null
            if (!$data['activeForum']) {
                $data['activeForum'] = $data['forums']->first(); // Fallback to the first forum if no active forum is found
            }
        }
        $data['activeCourseForum'] = 'active';
        $data['showCourseManagement'] = 'show';

        return view('instructor.courses.forum.view', $data);
    }


}
