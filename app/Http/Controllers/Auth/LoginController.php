<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }


    public function login()
    {

        return view('auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|min:8',
        ]);

        // Fetch the user with the given email and active status
        $user = User::where('email', $request->email)
            ->where('status', 1) // Ensure the user is active
            ->first();

        // Check if user exists and password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // Check if the user is authenticated
            if (Auth::check()) {
                // Redirect based on user role
                switch ($user->role_id) {
                    case 1:
                        return redirect()->route('admin.dashboard');
                    case 2:
                        return redirect()->route('instructor.dashboard');
                    case 3:
                        return redirect()->route('student.dashboard');
                    case 4:
                        return redirect()->route('superAdmin.dashboard');

                    default:
                        Auth::logout(); // Log out if role is not recognized
                        return redirect()->route('auth.login')->withErrors([
                            'email' => 'Invalid role specified.',
                        ]);
                }
            } else {
                return back()->withErrors([
                    'email' => 'Authentication failed. Please try again.',
                ]);
            }
        } else {
            return back()->withErrors([
                'email' => 'You are blocked. Contact admin.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function logout(Request $request)
    {
        $guard = Auth::getDefaultDriver(); // or Auth::guard('your-guard-name');

        // Check if the guard is set
        if ($guard) {
            // Log out the user
            Auth::guard($guard)->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Flash a success message
            $request->session()->flash('success', 'You have been logged out successfully!');
        }

        // Redirect to the login route
        return redirect()->route('login');
    }


}
