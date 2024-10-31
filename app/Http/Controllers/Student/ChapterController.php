<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request,$slug)
    {
           $data['course']= Course::with('chapters')->where('slug',$slug)->firstOrFail();
            $data['activeChapters']='active';
        return view('student.courses.chapters.index',$data);
    }
}
