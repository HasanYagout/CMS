<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\InstructorQuiz;
use App\Models\QuizQuestion;
use App\Models\StudentQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quizzes = InstructorQuiz::with('lecture.chapter.course')->where('instructor_id', Auth::id())->get();
            return datatables($quizzes)
                ->addIndexColumn()
                ->addColumn('title', function ($data) {
                    return $data->title;
                })
                ->addColumn('course', function ($data) {


                    return $data->lecture->chapter->course->name;
                })
                ->addColumn('chapter', function ($data) {

                    return $data->lecture->chapter->title;
                })
                ->addColumn('lecture', function ($data) {

                    return $data->lecture->title;
                })
                ->addColumn('due_date', function ($data) {

                    return $data->due_date;
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status ? 'checked' : '';
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input toggle-status" type="checkbox" data-id="' . $data->id . '" id="toggleStatus' . $data->id . '" ' . $checked . '>
                        <label class="form-check-label" for="toggleStatus' . $data->id . '"></label>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn('action', function ($data) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="d-flex gap-2">
                    <button onclick="getEditModal(\'' . route('instructor.courses.quiz.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#edit-modal" title="' . __('Upload') . '">
                <img src="' . asset('assets/images/icon/edit.svg') . '" alt="upload" />
            </button>
                    <button onclick="deleteItem(\'' . route('instructor.courses.quiz.delete', $data->id) . '\', \'departmentDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
                })
                ->rawColumns(['name', 'action', 'status'])
                ->make(true);
        }
        $data['courses'] = Availabilities::with('course')->where('instructor_id', Auth::id())->get();
        $data['showCourseManagement'] = 'show';
        $data['activeCourseQuiz'] = 'active';
        return view('instructor.courses.quiz.index', $data);
    }

    public function store(Request $request)
    {
        // Debugging line (remove in production)
        // dd($request->all());

        // Validate the incoming request
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'lecture_id' => 'required|exists:lectures,id',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1', // Ensure duration is a positive integer
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.options' => 'required|array|min:2', // Ensure there are at least 2 options
            'questions.*.options.*' => 'required|string', // Each option must be a string
            'questions.*.correct_answer' => 'required|string|in:Option 1,Option 2,Option 3,Option 4', // Adjust based on your options
        ]);

        // Save the quiz
        $quiz = InstructorQuiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'lecture_id' => $request->lecture_id,
            'duration' => $request->duration,
            'due_date' => $request->due_date,
            'grade' => $request->grade,
            'status' => 1,
            'instructor_id' => Auth::id(),
        ]);

        // Save questions
        foreach ($request->questions as $question) {
            $quiz->questions()->create([
                'instructor_quiz_id' => $quiz->id,
                'text' => $question['text'],
                'type' => 'mcq',
                'options' => json_encode($question['options']), // Save options as a JSON encoded string
                'correct_answer' => $question['correct_answer'],
            ]);
        }

        return redirect()->back()->with('success', 'Quiz created successfully!');
    }

    public function edit($id)
    {

        $data['quiz'] = InstructorQuiz::with('questions')->where('id', $id)->first();
        return view('instructor.courses.quiz.edit-form', $data);
    }


    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'duration' => 'required|integer',
            'grade' => 'required|integer',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:mcq,essay',
            'questions.*.options' => 'nullable|string',
            'questions.*.correct_answer' => 'nullable|string'
        ]);

        // Find the quiz
        $quiz = InstructorQuiz::find($id);
        // Update quiz details
        $quiz->title = $request->title;
        $quiz->due_date = $request->due_date;
        $quiz->duration = $request->duration;
        $quiz->grade = $request->grade;
        $quiz->save();

        // Update questions
        foreach ($request->questions as $index => $questionData) {
            $question = QuizQuestion::find($questionData['id']);
            $question->text = $questionData['text'];
            $question->type = $questionData['type'];
            $question->options = $questionData['type'] == 'mcq' ? explode(', ', $questionData['options']) : null;
            $question->correct_answer = $questionData['correct_answer'];
            $question->save();
        }

        return redirect()->route('instructor.courses.quiz.index')->with('success', 'Quiz updated successfully.');
    }


    public function updateStatus(Request $request)
    {
        InstructorQuiz::find($request->id)->update(['status' => $request->status]);
        return response()->json(['message' => 'Assignment status updated successfully.']);
    }

    public function delete(Request $request, $id)
    {

        $quiz = InstructorQuiz::find($id);
        $hasQuiz = StudentQuiz::where('instructor_quiz_id', $quiz->id)->exists();
        if ($hasQuiz) {
            return response()->json(['message' => 'Cannot delete quiz as it has associated submitted quizzes.'], 400);
        }
        $quiz->delete();
        return response()->json(['message' => 'Quiz deleted successfully.']);
    }
}
