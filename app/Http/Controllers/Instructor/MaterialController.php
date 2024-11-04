<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $material = Material::with('chapters');

            if ($request->has('chapter_id') && $request->chapter_id) {
                $material->where('chapter_id', $request->chapter_id);
            }

            return datatables($material->orderBy('id', 'desc'))
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return '<img width="60" src="' . asset('storage/materials/' . $data->url) . '" alt="icon" class="rounded avatar-xs max-h-35">';
                })
                ->addColumn('title', function ($data) {
                    return $data->title;
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
                    return '<button onclick="getEditModal(\'' . route('Admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Edit') . '">
                    <img src="' . asset('assets/images/icon/edit.svg') . '" alt="edit" />
                </button>';
                })
                ->rawColumns(['title', 'status', 'image', 'action'])
                ->make(true);
        }

        $data['showCourseManagement'] = 'show';
        $data['activeCourseMaterial'] = 'active';
        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        return view('instructor.courses.materials', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'titles.*' => 'required|string|max:255',
            'images.*' => 'required|file',
        ]);
        foreach ($request->titles as $index => $title) {
            $material = new Material();
            $material->chapter_id = $request->chapter_id;
            $material->title = $title;


            // Handle file upload
            if ($request->hasFile('images.' . $index)) {
                $date = now()->format('Ymd'); // Get current date in YYYYMMDD format
                $randomNumber = rand(100000, 999999); // Generate a random number
                $randomSlug = Str::random(10); // Generate a random string of 10 characters

                $file = $request->file('images.' . $index);
                $extension = $file->getClientOriginalExtension(); // Get the original file extension

                // Create the new file name
                $newFileName = "{$date}_{$randomSlug}_{$randomNumber}.{$extension}";

                // Store the file with the new name
                $path = $file->storeAs('materials', $newFileName, 'public');
                $material->url = $newFileName; // Store the file path
                $material->type = $extension; // Store the file extension

            }

            $material->save();
        }

    }

}
