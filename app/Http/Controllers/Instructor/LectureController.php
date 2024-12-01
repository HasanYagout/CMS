<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $selectedCourse = $request->get('selectedCourse');
            $user = Auth::user();
            $query = Lecture::whereHas('chapter.course.availability', function ($query) use ($user) {
                $query->where('instructor_id', $user->id);
            });
            if ($selectedCourse) {
                $query->whereHas('chapter.course', function ($query) use ($selectedCourse) {
                    $query->where('id', $selectedCourse);
                });
            }
            $lectures = $query->get();


            return datatables($lectures)
                ->addIndexColumn()
                ->addColumn('course', function ($data) {

                    return $data->chapter->course->name;
                })
                ->addColumn('chapter', function ($data) {

                    return $data->chapter->title;
                })
                ->addColumn('lecture', function ($data) {
                    return $data->title;
                })
                ->addColumn('zoom_link', function ($data) {
                    return $data->zoom_link;
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
                    <button onclick="getEditModal(\'' . route('instructor.courses.lectures.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.lectures.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['title', 'status', 'course', 'action'])
                ->make(true);
        }
        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        $data['showCourseManagement'] = 'show';
        $data['activeCourseLecture'] = 'active';
        return view('instructor.courses.lectures.index', $data);
    }

    public function store(Request $request)
    {

        $request->validate(['course_id' => 'required|exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'zoom_link' => 'nullable|url',
        ]);

        $lecture = new Lecture();
        $lecture->chapter_id = $request->chapter_id;
        $lecture->title = $request->title;
        $lecture->status = 1;
        $lecture->description = $request->description;
        $lecture->zoom_link = $request->zoom_link;
        $lecture->save();

        // Save materials


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Extract the original file name without extension
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                // Generate a slug from the original file name
                $slug = Str::slug($originalName);

                // Append a random number to the slug
                $randomNumber = rand(1000, 9999);
                $newFileName = $slug . '_' . $randomNumber . '.' . $image->getClientOriginalExtension();

                // Store the file with the new name
                $path = $image->storeAs('materials', $newFileName, 'public');
                
                // Create the material record
                Material::create([
                    'lecture_id' => $lecture->id,
                    'title' => $newFileName,
                    'type' => 'image',
                    'url' => $path,
                ]);
            }
        }


        return redirect()->route('instructor.courses.lectures.index')->with('success', 'Lecture added successfully.');
    }

    public function getLecturesByCourseId($id)
    {
        return Lecture::where('chapter_id', $id)->orderBy('created_at', 'DESC')->get();
    }

    public function getLastLecture($courseId)
    {
        $lastLecture = Lecture::whereHas('chapters.course', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->first();
        return response()->json($lastLecture);
    }

    public function edit($id)
    {
        $data['lecture'] = Lecture::with('chapter.course.availability')->where('id', $id)->first();
        return view('instructor.courses.lectures.edit-form', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'zoom_link' => 'required|url',
            'description' => 'required|string',
        ]);
        $lecture = Lecture::find($id);
        $lecture->title = $request->title;
        $lecture->zoom_link = $request->zoom_link;
        $lecture->description = $request->description;
        $lecture->save();

        return redirect()->route('instructor.courses.lectures.index')->with('success', 'Lecture updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        Lecture::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Lecture status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $lecture = Lecture::find($id);
        $hasMaterial = Material::where('lecture_id', $lecture->id)->exists();
        if ($hasMaterial) {
            return response()->json(['message' => 'Cannot delete lecture as it has associated materials.'], 400);
        }
        $lecture->delete();
        return response()->json(['message' => 'Lecture deleted successfully.']);
    }


}
