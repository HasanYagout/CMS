<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $courseId = $request->get('course_id');

            $query = Chapter::with('course')->where('instructor_id', Auth::id());
            if ($courseId) {
                $query->where('course_id', $courseId); // Filter by course ID
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
                                    <button onclick="getEditModal(\'' . route('instructor.courses.materials.index', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo">
                                        <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="edit" />
                                    </button>
                                </li>
                            </ul>';
                })
                ->rawColumns(['title','status','course','action'])
                ->make(true);
        }
        $data['showCourseManagement']='show';
        $data['activeCourseChapter']='active';

        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        return view('instructor.courses.chapter', $data);
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
                'status'=>1
            ]);
        }
        return redirect()->back()->with('success', 'Chapters created successfully!');
    }

    public function status(Request $request, $id)
    {
        Chapter::where('id', $id)->update(['status'=>$request->status]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Chapter Updated Successfully']);
        }
        session()->flash('success', 'Chapter Updated Successfully');
        return back();
    }
    public function getChapterByCourseId($id)
    {

        return Chapter::with('course')->where('course_id',$id)->orderBy('created_at','DESC')->get();
    }


}
