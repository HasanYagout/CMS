<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index($courseId)
    {
        $data['quizzes'] = InstructorQuiz::whereHas('lecture.chapters.course', function($query) use ($courseId) {
            $query->where('id', $courseId);
        })->with(['lecture','submittedQuiz'])->get();

        $data['activeQuizzes']='active';
        return view('student.courses.quiz.index',$data);
    }
    public function show(InstructorQuiz $quiz)
    {
        $data['quiz']=$quiz;
      return view('student.enrollment.quiz.view', $data);
    }

    public function remaining_time(Request $request)
    {
        dd($request->all());
    }
}
