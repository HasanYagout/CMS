<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $data['courses']=Availabilities::with('course')->where('instructor_id',Auth::id())->get();

        return view('instructor.courses.quiz.index',$data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'duration' => 'required|integer|min:1', // Ensure duration is a positive integer
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string|in:mcq,essay',
            'questions.*.options' => 'nullable|string',
            'questions.*.correct_answer' => 'nullable|string',
        ]);

        // Save the quiz
        $quiz = InstructorQuiz::create([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'chapter_id' => $request->chapter_id,
            'duration' => $request->duration, // Save the duration
        ]);

        // Save questions
        foreach ($request->questions as $question) {
            $quiz->questions()->create([
                'text' => $question['text'],
                'type' => $question['type'],
                'options' => $question['options'],
                'correct_answer' => $question['correct_answer'],
            ]);
        }

        return redirect()->back()->with('success', 'Quiz created successfully!');
    }
}
