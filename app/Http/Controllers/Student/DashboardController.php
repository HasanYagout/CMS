<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Chapter;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\News;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
//        $data['activeHome']='active';
//
//        $studentId = Auth::id(); // Assuming the student is logged in
//        $data['assignments'] = InstructorAssignments::with('lecture')
//            ->whereIn('lecture_id', function ($query) use ($studentId) {
//                $query->select('course_id')
//                    ->from('enrollment')
//                    ->where('student_id', $studentId);
//
//            })
//            ->whereBetween('due_date', [
//                Carbon::now()->format('Y-m-d'),
//                Carbon::now()->addDays(3)->format('Y-m-d')
//            ])->get();
//
//        $data['quizzes'] = InstructorQuiz::with('course')
//            ->whereIn('lecture_id', function ($query) use ($studentId) {
//                $query->select('course_id')
//                    ->from('enrollment')
//                    ->where('student_id', $studentId);
//            })
//            ->whereBetween('due_date', [
//                Carbon::now()->format('Y-m-d'),
//                Carbon::now()->addDays(3)->format('Y-m-d')
//            ])->get();
//
//        $data['attentions']=$data['assignments']->merge($data['quizzes']);
//        $data['days']=[];
//        $data['hours']=[];
//        foreach ($data['attentions'] as $index=>$attention) {
//            $difference=Carbon::now()->diff($attention->due_date);
//            $data['days'][$index]=$difference->d;
//            $data['hours'][$index]=$difference->h;
//        }
//
//        $data['lectures']=Chapter::with('course')
//            ->whereIn('course_id', function ($query) use ($studentId) {
//                $query->select('course_id')
//                    ->from('enrollment')
//                    ->where('student_id', $studentId);
//            })
//            ->get();
//        $data['announcements']=Announcement::with('course')
//            ->whereIn('course_id', function ($query) use ($studentId) {
//                $query->select('course_id')
//                    ->from('enrollment')
//                    ->where('student_id', $studentId);
//            })
//            ->get();
        $data['student'] = Student::where('user_id', Auth::id())->first();
        $student = Auth::user()->student;
        $collegeId = $student->college_id;
        $data['news'] = News::where('college_id', $collegeId)->get();
        $data['announcements'] = Announcement::whereHas('course.enrollments', function ($query)  {
            $query->where('student_id', Auth::id());
        })->count();

        $data['assignments'] = InstructorAssignments::whereHas('lecture.chapter.course.enrollments', function ($query)  {
            $query->where('student_id', Auth::id());
        })->count();

        $data['quizzes'] = InstructorQuiz::whereHas('lecture.chapter.course.enrollments', function ($query)  {
            $query->where('student_id', Auth::id());
        })->count();

        return view('student.dashboard',$data);
    }
}
