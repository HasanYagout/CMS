<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'message' => 'required|string',
        ]);

        Chat::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back();
    }
}
