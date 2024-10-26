<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(InstructorQuiz $quiz)
    {
        $data['quiz']=$quiz;
      return view('student.quiz.index', $data);
    }

    public function remaining_time(Request $request)
    {
        dd($request->all());
    }
}
