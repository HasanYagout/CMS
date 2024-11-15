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
        $validatedData = $request->validate([
            'comment' => 'required|string|max:255',
//            'assignments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip', // Adjust mime types and size as needed
        ]);

        $filePaths = [];

        // Handle file uploads
        if ($request->hasFile('assignments')) {
            foreach ($request->file('assignments') as $file) {
                // Generate a random string
                $randomString = Str::random(10);
                // Generate a random slug
                $randomSlug = Str::slug(Str::random(5));
                // Generate a random number
                $randomNumber = rand(1000, 9999);
                // Get the file extension
                $extension = $file->getClientOriginalExtension();
                // Combine them into a new file name
                $newFileName = "{$randomString}_{$randomSlug}_{$randomNumber}.{$extension}";

                $file->move(public_path('storage/assignments'), $newFileName); // Save the file to the specified path


                $filePaths[] = str_replace('public/', '', $newFileName);
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
