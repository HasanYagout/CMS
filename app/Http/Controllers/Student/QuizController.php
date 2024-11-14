<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\InstructorQuiz;
use App\Models\Lecture;
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
        // Validate the request to ensure it contains questions and each has a corresponding answer
        $request->validate([
            'quiz_id' => 'required|integer|exists:instructor_quiz,id',
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);

        $totalScore = 0;

        // Loop through each question answer
        foreach ($request->questions as $questionId => $answer) {
            $question = QuizQuestion::with('quiz')->find($questionId);
            if ($question) {
                $grade = $question->quiz->grade;
                $questions_count = $question->quiz->questions()->count();
                $calculate_grade = $grade / $questions_count;

                // Decode the options from JSON
                $options = json_decode($question->options);
                // Determine the correct answer's index (assuming "Option 1", "Option 2", etc.)
                $correctAnswer = $question->correct_answer;
                $correctAnswerIndex = array_search($correctAnswer, [
                    "Option 1",
                    "Option 2",
                    "Option 3",
                    "Option 4"
                ]);

                $correctOption = $options[$correctAnswerIndex] ?? null;

                // Check if the answer matches the correct answer
                if ($correctOption && trim($correctOption) === trim($answer)) {
                    $totalScore += $calculate_grade;
                }
            }
        }

        // Save the student's score
        StudentQuiz::create([
            'instructor_quiz_id' => $request->quiz_id,
            'student_id' => Auth::id(),
            'grade' => $totalScore,
        ]);

        // Retrieve the quiz to get the course and lecture ID
        $quiz = InstructorQuiz::find($request->quiz_id);
        $lectureId = $quiz->lecture_id;
        $lecture = Lecture::with('chapter.course')->find($lectureId);
        $courseId = $lecture->chapter->course->id;

        return redirect()->route('student.courses.lectures.view', ['course_id' => $courseId, 'lecture_id' => $lectureId])
            ->with('success', 'Quiz submitted successfully.');
    }
}
