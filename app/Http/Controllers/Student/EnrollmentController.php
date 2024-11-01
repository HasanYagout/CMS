<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Chapter;
use App\Models\Chat;
use App\Models\Course;
use App\Models\Enrollment;
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
        $courseId = Course::where('slug', $slug)->value('id');
        $studentId = Auth::id(); // Assuming the student is logged in

        // Get assignments for the logged-in student within the next 3 days
        $data['assignments'] = InstructorAssignments::with('lecture')
            ->whereHas('lecture.chapters.course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
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
            ->whereHas('lecture.chapters.course', function ($query) use ($courseId) {
                $query->where('id', $courseId);
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

        $data['attentions'] = $data['assignments']->merge($data['quizzes']);
        $data['days'] = [];
        $data['hours'] = [];
        foreach ($data['attentions'] as $index => $attention) {
            $difference = Carbon::now()->diff($attention->due_date);
            $data['days'][$index] = $difference->d;
            $data['hours'][$index] = $difference->h;
        }

        $data['lectures'] = Lecture::whereHas('chapters.course', function ($query) use ($courseId) {
            $query->where('id', $courseId);
        })->with('chapters.course')->get();

        $data['announcements'] = Announcement::with('course')
            ->where('course_id', $courseId)
            ->whereIn('course_id', function ($query) use ($studentId) {
                $query->select('course_id')
                    ->from('enrollment')
                    ->where('student_id', $studentId);
            })
            ->get();

        $data['chats'] = Chat::where('course_id', $courseId)->with('user.instructor','user.student')->get();

        return view('student.enrollment.dashboard', $data);
    }


    public function courses()
    {
        $studentId = Auth::id();

        $data['courses']= Course::whereHas('enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId)
                ->whereHas('payment', function ($query) {
                    $query->where('is_paid', true);
                });
        })
            ->with(['availability.instructor']) // Eager load the instructor
            ->get();

        return view('student.enrollment.index', $data);
    }
    public function register(Request $request,$id)
    {
        $enrollment_id  =Enrollment::insertGetId(
            ['student_id'=>Auth::id(),
             'course_id'=>$id,
            ]
        );
        Payment::create(
            ['enrollment_id'=>$enrollment_id,
             ]
        );

    }

    public function info($slug)
    {


    }

    public function view()
    {
        return view('student.enrollment.course');
    }
}
