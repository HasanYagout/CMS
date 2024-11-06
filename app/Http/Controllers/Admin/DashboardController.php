<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['courses']=Course::where('department_id',Auth::user()->admin->department_id)->count();
        $data['instructors']=Instructor::where('department_id',Auth::user()->admin->department_id)->count();
        $data['students']=Student::where('department_id',Auth::user()->admin->department_id)->count();

        $data['activeHome']='active';
        return view('admin.dashboard',$data);
    }
}
