<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index()
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

    public function view($id)
    {
        $assignments = InstructorAssignments::where('course_id', $id)
            ->where('due_date', '=', Carbon::now()->addDays(3)->format('Y-m-d'))
            ->get();
        $quiz=InstructorQuiz::where('course_id', $id)
                ->where('due_date', '=', Carbon::now()->addDays(3)->format('Y-m-d'))
                ->get();
        $data['attentions']=$assignments->merge($quiz);
        $data['days']=[];
        $data['hours']=[];
        foreach ($data['attentions'] as $attention) {
            $difference=Carbon::now()->diff($attention->due_date);
            $data['days']=$difference->d;
            $data['hours']=$difference->h;
        }
        $data['lectures']=Chapter::where('course_id', $id)->get();

        return view('student.enrollment.course',$data);
    }
}
