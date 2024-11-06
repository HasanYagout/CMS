<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Availabilities;
use App\Models\InstructorActivity;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use App\Models\Lecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data['activeHome'] = 'active';
        $data['courses'] = Availabilities::where('instructor_id', Auth::id())->distinct('course_id')->count('course_id');
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $data['lectures'] = Lecture::whereHas('chapter.course.availability', function ($query) {
            $query->where('instructor_id', Auth::id());
        })
            ->where(function ($query) use ($today, $tomorrow) {
                $query->whereDate('start_date', $today)
                    ->orWhereDate('start_date', $tomorrow);
            })->with('chapter.course')
            ->get();

        $data['assignments'] = InstructorAssignments::whereHas('lecture.chapter.course.availability', function ($query) {
            $query->where('instructor_id', Auth::id());
        })->where(function ($query) use ($today, $tomorrow) {
            $query->whereDate('due_date', $today)
                ->orWhereDate('due_date', $tomorrow);
        })->with('lecture.chapter.course')
            ->get();

        $data['quizzes'] = InstructorQuiz::whereHas('lecture.chapter.course.availability', function ($query) {
            $query->where('instructor_id', Auth::id());
        })
            ->where(function ($query) use ($today, $tomorrow) {
                $query->whereDate('due_date', $today)
                    ->orWhereDate('due_date', $tomorrow);
            })->with('lecture.chapter.course')
            ->get();

        $data['activities'] = InstructorActivity::whereHas('lecture.chapter.course.availability', function ($query) {
            $query->where('instructor_id', Auth::id());
        })
            ->where(function ($query) use ($today, $tomorrow) {
                $query->whereDate('due_date', $today)
                    ->orWhereDate('due_date', $tomorrow);
            })
            ->get();

        return view('instructor.dashboard', $data);
    }
}
