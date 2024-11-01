<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Lecture;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LectureController extends Controller
{
    public function index()
    {
        $data['courses']=Availabilities::with('course')->where('instructor_id',Auth::id())->get();
        return view('instructor.courses.lectures.index',$data);
    }

    public function store(Request $request)
    {

        // Validate the incoming request
//        $request->validate([
//            'course_id' => 'required|exists:courses,id',
//            'chapter_id' => 'required|exists:chapters,id',
//            'title' => 'required|string|max:255',
//            'description' => 'nullable|string',
//            'images.*' => 'nullable|mimes:jpg,jpeg,png,svg,gif,mp4,mov,avi,mkv,webm,flv|max:20480',
//            'zoom_link' => 'nullable|url',
//        ]);

        // Create the lecture
        $lecture = Lecture::create([
            'chapter_id' => $request->chapter_id,
            'title' => $request->title,
            'description' => $request->description,
            'zoom_link' => $request->zoom_link,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
        ]);

        // Save materials
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filePath = $file->store('materials');

                Material::create([
                    'lecture_id' => $lecture->id,
                    'title' => $file->getClientOriginalName(),
                    'type' => $file->getClientOriginalExtension() === 'mp4' ? 'video' : 'image',
                    'url' => $filePath,
                ]);
            }
        }

        return redirect()->route('instructor.courses.lectures.index')->with('success', 'Lecture added successfully.');
    }

    public function getLecturesByCourseId($id)
    {
        return Lecture::where('chapter_id',$id)->orderBy('created_at','DESC')->get();
    }


}
