<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Enrollment;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index($courseId)
    {

        $data['Announcements']=Announcement::where('course_id',$courseId)
            ->get();
        $data['activeAnnouncement']='active';
        return view('student.courses.announcement.index',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'announcement_id' => 'required|exists:announcement,id',
            'choice' => 'required|string',
        ]);

        $announcement = Announcement::find($request->announcement_id);
        $choices = json_decode($announcement->choices, true);

        // Check if the student has already voted
        $vote = Vote::where('announcement_id', $request->announcement_id)
            ->where('student_id', Auth::id())
            ->first();

        if ($vote) {
            // Decrement the count of the previous choice
            foreach ($choices as &$choice) {
                if ($choice['name'] === $vote->choice) {
                    $choice['count']--;
                }
            }
            $vote->delete();
        }

        // Increment the count of the new choice
        foreach ($choices as &$choice) {
            if ($choice['name'] === $request->choice) {
                $choice['count']++;
            }
        }

        // Save the updated choices and vote
        $announcement->choices = json_encode($choices);
        $announcement->save();

        Vote::create([
            'announcement_id' => $request->announcement_id,
            'student_id' => Auth::id(),
            'choice' => $request->choice,
        ]);

        return redirect()->back()->with('success', 'Your vote has been submitted successfully.');
    }
}
