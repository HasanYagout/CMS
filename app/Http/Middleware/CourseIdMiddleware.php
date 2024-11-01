<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CourseIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $course = Course::where('slug', $request->route('slug'))->first();
        Session::put('course_id', $course->id);
        Session::put('course_slug', $course->slug);

        if ($course) {
            // Share the course_id with all views
            View::share('course_id', $course->id);
            View::share('course_slug', $course->slug);
        }
        return $next($request);
    }
}
