<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AssignmentController extends Controller
{

    public function store(Request $request, $id)
    { // Validate the request
        $request->validate([
            'comment' => 'required|string|max:255',
            'assignments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', // Adjust as needed
        ]);

        // Create the assignment
        $assignment = new StudentAssignment();
        $assignment->student_id = Auth::id();
        $assignment->instructor_assignment_id = $id;
        $assignment->comment = $request->input('comment'); // Store the comment

        // Handle file uploads
        if ($request->hasFile('assignments')) {
            $filePaths = []; // Array to hold file paths

            foreach ($request->file('assignments') as $file) {
                // Create a unique name for the file
                $slug = Str::slug($request->input('comment'), '-'); // Slug from the comment
                $randomText = Str::random(10); // Random text
                $randomNumber = rand(1000, 9999); // Random number
                $extension = $file->getClientOriginalExtension(); // Get the file extension

                // Create the file name
                $fileName = "{$slug}_{$randomText}_{$randomNumber}.{$extension}";

                // Store the file and save the path
                $filePath = $file->storeAs('uploads/assignments', $fileName);
                $filePaths[] = $fileName; // Add to the array
            }

            // Store the paths as a JSON string in the database
            $assignment->path = json_encode($filePaths);
        }
        $assignment->grade=0;

        // Save the assignment
        $assignment->save();

        return redirect()->back()->with('success', 'Assignment submitted successfully.');
    }
}
