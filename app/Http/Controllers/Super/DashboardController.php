<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Availabilities;
use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\Semester;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $data['courses'] = Course::count();
        $data['instructors'] = Instructor::count();
        $data['students'] = Student::count();
        $data['admins'] = Admin::where('user_id', '!=', Auth::id())->count();
        $data['departments'] = Department::count();
        $data['semesters'] = Semester::count();
        $data['activeHome'] = 'active';
        return view('super.dashboard', $data);
    }
}
