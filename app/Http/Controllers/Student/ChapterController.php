<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstructorAssignments;
use App\Models\InstructorQuiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function index(Request $request, $slug)
    {
        // Eager load chapters with lectures that have status 1
        $data['course'] = Course::where('slug', $slug)
            ->with(['chapters' => function ($query) {
                $query->with(['lectures' => function ($query) {
                    $query->where('status', 1);
                }]);
            }])
            ->firstOrFail();

        $data['activeChapters'] = 'active';
        return view('student.courses.chapters.index', $data);
    }

}
