<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Material;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $course = Course::with('semester')->orderBy('id', 'desc')->get();
            return datatables($course)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('image', function ($data) {
                    return $data->image;
                })
                ->addColumn('semester', function ($data) {

                    return $data->semester->name;
                })
                ->addColumn('start_date', function ($data) {
                    return $data->start_date;
                })
                ->addColumn('end_date', function ($data) {
                    return $data->end_date;
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
                    return '<button onclick="getEditModal(\'' . route('admin.courses.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="upload" />
            </button>';
                })
                ->rawColumns(['name','semester','image','action','status'])
                ->make(true);
        }
        $data['showCourseManagement']='show';
        $data['activeCourseALL']='active';
        return view('admin.courses.index',$data);
    }

    public function edit($id)
    {
        $course=Course::with('instructor')->find($id);
        $instructors=Instructor::all();
        $availabilities=Availabilities::all();
        $semesters=Semester::all();
        return view('admin.courses.edit-form',compact('course','instructors','availabilities','semesters'));
    }

    public function create()
    {
        $data['availabilities']=Availabilities::with('instructor')->get();
        $data['instructors']=Instructor::all();
        $data['semesters']=Semester::all();
        return view('admin.courses.create',$data);
    }

    public function store(Request $request)
    {
      $course= $request->validate([
          'name' => 'required',
           'image'=>'required',
           'semester_id' => 'required|exists:academic_years,id',
           'description' => 'required',
       ]);
        $image = NULL;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $date = now()->format('Ymd'); // Get current date in YYYYMMDD format
            $randomSlug = Str::random(10); // Generate a random string of 10 characters
            $randomNumber = rand(100000, 999999); // Generate a random number

            $fileName = $date . '_' . $randomSlug . '_' . $randomNumber . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/courses'), $fileName); // Save the file to the specified path

            $image = $fileName; // Save only the file name to the database
        }

      $course['status']=1;
      $course['image']=$image;
      $course['start_date']=$request->start_date;
      $course['end_date']=$request->end_date;
      $course['lectures']=$request->lectures;
      $course['hours']=$request->hours;
      $course['slug']=Str::slug($course['name']).'-'.Str::random(6);
      Course::create($course);
        session()->flash('success', 'Course Created Successfully');
        return back();

    }

    public function update()
    {

    }

    public function chapter(Request $request)
    {
        if ($request->ajax()) {
            $courseId = $request->get('course_id'); // Get the course ID from the request
            $query = Chapter::with('course');

            if ($courseId) {
                $query->where('course_id', $courseId); // Filter by course ID
            }

            $chapters = $query->get(); // Get filtered chapters

            return datatables($chapters)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    return $data->id;
                })
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
                    <button onclick="getEditModal(\'' . route('admin.courses.materials.index', $data->id) . '\'' . ', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo">
                        <img src="' . asset('public/assets/images/icon/edit.svg') . '" alt="edit" />
                    </button>

                </li>
            </ul>';
                })
                ->rawColumns(['title','status','course','action'])
                ->make(true);
        }

        $data['courses'] = Course::with('instructor')->get();
        return view('admin.courses.chapters', $data);
    }

    public function store_chapter(Request $request)
    {

       $request->validate([
            'titles' => 'required',
            'course_id' => 'required|exists:courses,id',
        ]);

        foreach ($request->titles as $title) {
            Chapter::create([
                'title' => $title,
                'course_id' => $request->course_id,
                'status'=>1
            ]);
        }

    }





    public function instructors()
    {
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
        return view('admin.courses.instructors',$data);
    }

    public function info($id)
    {
        $course = Course::find($id);

        $instructors = User::where('role_id', 2)
            ->where('status', 1)
            ->whereHas('instructor', function ($query) {
                $query->where('department_id', Auth::user()->admin->department_id);
            })->with('instructor')
            ->get();

        return response()->json([
            'course' => $course,
            'instructors' => $instructors,
        ]);
    }




}
