<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InstructorQuiz;
use App\Models\QuizQuestion;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index($courseId)
    {
        $data['quizzes'] = InstructorQuiz::whereHas('lecture.chapters.course', function ($query) use ($courseId) {
            $query->where('id', $courseId);
        })->with(['lecture.chapters.course', 'submittedQuiz'])->get();
        $data['activeQuizzes'] = 'active';
        return view('student.courses.quiz.index', $data);
    }

    public function show(InstructorQuiz $quiz)
    {
        $data['quiz'] = $quiz;

        return view('student.courses.quiz.view', $data);
    }

    public function remaining_time(Request $request)
    {
        dd($request->all());
    }

    public function store(Request $request)
    {
        $totalQuestions = count($request->questions);
        $totalScore = 0;

        // Loop through each question answer
        foreach ($request->questions as $questionId => $answer) {
            $question = QuizQuestion::with('quiz')->find($questionId);

            if ($question) {
                $grade = $question->quiz->grade;
                $questions_count = $question->quiz->questions()->count();
                $calculate_grade = $grade / $questions_count;

                // Check if the question exists and compare the answer
                if ($question->correct_answer == $answer) {
                    $totalScore += $calculate_grade;
                }
            }
        }
        StudentQuiz::create([
            'instructor_quiz_id' => $request->quiz_id,
            'student_id' => Auth::id(),
            'grade' => $totalScore,
        ]);

        return redirect()->route('student.courses.quizzes.show', $request->quiz_id);
    }

}
