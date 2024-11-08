<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InstructorAssignments;
use App\Models\Lecture;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AssignmentController extends Controller
{
    public function index($courseId)
    {
        $data['lectures'] = Lecture::whereHas('chapters.course', function ($query) use ($courseId) {
            $query->where('id', $courseId);
        })->with(['chapters.course', 'assignments.submittedAssignments'])->get();
        $data['activeAssignment'] = 'active';
        return view('student.courses.assignments.index', $data);
    }

    public function store(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'comment' => 'required|string|max:255',
            'assignments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Initialize an array to hold file paths
        $filePaths = [];

        // Handle file uploads
        if ($request->hasFile('assignments')) {
            foreach ($request->file('assignments') as $file) {
                // Store the file using a unique name
                $filePath = $file->store('assignments');
                $filePaths[] = $filePath;
            }
        }

        // Create the assignment entry in the database
        $assignment = StudentAssignment::create([
            'student_id' => Auth::id(),
            'instructor_assignments_id' => $id,
            'comment' => $validatedData['comment'],
            'grade' => 0, // Initialize grade to 0
            'path' => json_encode($filePaths), // Store file paths directly here
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Assignment submitted successfully.');
    }
}
