<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $data['activeProfile'] = 'active';
        $data['user'] = Auth::user();
        return view('student.profile', $data);
    }

    public function update(Request $request)
    {
        // Validate the request to ensure the image is present and is a valid image file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate file type and size
        ]);

        // Check if the request contains a file
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $date = now()->format('Ymd'); // Get current date in YYYYMMDD format
            $randomSlug = Str::random(10); // Generate a random string of 10 characters
            $randomNumber = rand(100000, 999999); // Generate a random number

            $fileName = $date . '_' . $randomSlug . '_' . $randomNumber . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/profile'), $fileName); // Save the file to the specified path

            $image = $fileName;
            User::find(Auth::id())->update(['image' => $image]);

            return redirect()->route('student.profile')->with('success', 'Profile Updated successfully.');
        }

        return redirect()->route('student.profile')->with('error', 'No image file was uploaded.');
    }


    public function password(Request $request)
    {
        $request->validate([
            'previous_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the previous password matches
        if (!Hash::check($request->previous_password, $user->password)) {
            return back()->with('error', 'Previous password does not match.');
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log the user out
        Auth::logout();

        // Redirect to login page with a message
        return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
    }
}
