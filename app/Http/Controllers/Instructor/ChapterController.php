<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $selectedCourse = $request->get('selectedCourse');

            $query = Chapter::whereHas('course.availabilities', function ($query) {
                $query->where('instructor_id', Auth::id());
            });

            // Filter by selected course if it's provided
            if ($selectedCourse) {

                $query->where('course_id', $selectedCourse);
            }

            $chapters = $query->get(); // Get filtered chapters

            return datatables($chapters)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
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
                    <button onclick="getEditModal(\'' . route('instructor.courses.chapters.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.chapters.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['title', 'status', 'course', 'action'])
                ->make(true);
        }
        $data['showCourseManagement'] = 'show';
        $data['activeCourseChapter'] = 'active';

        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        return view('instructor.courses.chapters.index', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'chapters' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);

        foreach ($request->chapters as $chapter) {
            Chapter::create([
                'title' => $chapter,
                'course_id' => $request->course_id,
                'instructor_id' => Auth::id(),
                'status' => 1
            ]);
        }
        return redirect()->back()->with('success', 'Chapters created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        Chapter::where('id', $id)->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Chapter Updated Successfully']);
        }
        session()->flash('success', 'Chapter Updated Successfully');
        return back();
    }

    public function getChaptersAndAvailability($courseId)
    {
        $chapters = Chapter::where('course_id', $courseId)->get();
        $course = Course::with('availabilities')->find($courseId);

        return response()->json([
            'chapters' => $chapters,
            'availabilities' => $course->availabilities,
            'course' => $course,
        ]);
    }

    public function getLastLecture($courseId)
    {
        $lastLecture = Lecture::where('course_id', $courseId)->orderBy('start_date', 'desc')->first();
        return response()->json($lastLecture);
    }

    public function getChapters($courseId)
    {
        return Chapter::where('course_id', $courseId)->get();
    }


    public function getAvailability($courseId)
    {
        $course = Course::find($courseId);
        $availability = Availabilities::where('course_id', $courseId)->first();
        return response()->json(['course' => $course, 'availability' => $availability]);
    }

    public function edit($id)
    {
        $data['chapter'] = Chapter::with('course')->where('id', $id)->first();

        $data['availabilities'] = Availabilities::where('instructor_id', Auth::id())->whereHas('course', function ($query) {
            $query->where('department_id', Auth::user()->instructor->department_id);
        })->with(['course' => function ($query) {
            $query->select('id', 'name');
        }])->get();

        return view('instructor.courses.chapters.edit-form', $data);
    }


    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);
        $chapter = Chapter::find($id);
        $chapter->title = $request->title;
        $chapter->course_id = $request->course_id;
        $chapter->save();
        return redirect()->route('instructor.courses.chapters.index')->with('success', 'Chapter updated successfully.');

    }

    public function delete(Request $request, $id)
    {
        $chapter = Chapter::find($id);
        $hasLectures = Lecture::where('chapter_id', $chapter->id)->exists();
        if ($hasLectures) {
            return response()->json(['message' => 'Cannot delete chapter as it has associated lectures.'], 400);

        }
        $chapter->delete();
        return response()->json(['message' => 'Chapter deleted successfully.']);
    }


}
