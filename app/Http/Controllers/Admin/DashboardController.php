<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['courses']=Availabilities::where('instructor_id',Auth::id())->get();
//        dd($data);
        return view('admin.dashboard');
    }
}
