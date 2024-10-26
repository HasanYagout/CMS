<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request,$id)
    {

       $data['assignments']=InstructorAssignments::where('chapter_id',$id)->get();
       $data['quizzes']=InstructorQuiz::with('questions')
           ->where('instructor_id',Auth::id())
           ->where('chapter_id',$id)
           ->get();

        return view('student.chapter.index',$data);
    }
}
