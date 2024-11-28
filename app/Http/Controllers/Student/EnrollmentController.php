<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Chapter;
use App\Models\Chat;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Forum;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\Lecture;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index($slug)
    {
        $data['activeHome'] = 'active';
        $data['course'] = Course::where('slug', $slug)->first();

        $studentId = Auth::id(); // Assuming the student is logged in

        // Get assignments for the logged-in student within the next 3 days
        $data['assignments'] = InstructorAssignments::with('lecture')
            ->whereHas('lecture.chapters.course', function ($query) use ($data) {
                $query->where('id', $data['course']->id);
            })
            ->whereIn('lecture_id', function ($query) use ($studentId) {
                $query->select('lecture_id')
                    ->from('enrollment')
                    ->where('student_id', $studentId);
            })
            ->whereBetween('due_date', [
                Carbon::now()->format('Y-m-d'),
                Carbon::now()->addDays(3)->format('Y-m-d')
            ])
            ->get();

        // Get quizzes for the logged-in student within the next 3 days
        $data['quizzes'] = InstructorQuiz::with('course')
            ->whereHas('lecture.chapters.course', function ($query) use ($data) {
                $query->where('id', $data['course']->id);
            })
            ->whereIn('lecture_id', function ($query) use ($studentId) {
                $query->select('lecture_id')
                    ->from('enrollment')
                    ->where('student_id', $studentId);
            })
            ->whereBetween('due_date', [
                Carbon::now()->format('Y-m-d'),
                Carbon::now()->addDay(1)->format('Y-m-d')
            ])
            ->get();
        $data['attentions'] = $data['assignments']->merge($data['quizzes']);
        $data['days'] = [];
        $data['hours'] = [];
        foreach ($data['attentions'] as $index => $attention) {
            $difference = Carbon::now()->diff($attention->due_date);
            $data['days'][$index] = $difference->d;
            $data['hours'][$index] = $difference->h;
        }

        $data['lectures'] = Lecture::whereHas('chapter.course', function ($query) use ($data) {
            $query->where('id', $data['course']->id);
        })
            ->whereBetween('start_date', [Carbon::today(), Carbon::today()->addDays(3)])
            ->with('chapter.course')
            ->get();


        $data['announcements'] = Announcement::with('course')
            ->where('course_id', $data['course']->id)
            ->whereIn('course_id', function ($query) use ($studentId) {
                $query->select('course_id')
                    ->from('enrollment')
                    ->where('student_id', $studentId);
            })
            ->get();

        $data['forums'] = Forum::where('course_id', $data['course']->id)->get();

        return view('student.enrollment.dashboard', $data);
    }


    public function courses()
    {
        $studentId = Auth::id();

        $data['courses'] = Course::whereHas('enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId)
                ->where('status', 1)
                ->whereHas('payment', function ($query) {
                    $query->where('is_paid', true);
                });
        })
            ->with(['availability.instructor']) // Eager load the instructor
            ->get();
        $data['activeEnrolled'] = 'active';
        return view('student.enrollment.index', $data);
    }

    public function register(Request $request, $id)
    {
        $course = Course::find($id);
        if ($course) {

            $enrollment_id = Enrollment::insertGetId(
                [
                    'student_id' => Auth::id(),
                    'course_id' => $id,
                    'status' => false
                ]
            );
            Payment::create(
                ['enrollment_id' => $enrollment_id,
                    'is_paid' => true,
                    'student_id' => Auth::id()
                ]
            );
            return redirect()->route('student.courses.info', $course->slug)->with('Course Registered successfully');
        } else {
            return back()->withErrors('Course Not Found');

        }


    }

    public function info($slug)
    {


    }

    public function view()
    {
        return view('student.enrollment.course');
    }
}
