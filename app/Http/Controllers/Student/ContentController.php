<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Material;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index()
    {
        $data['lectures']=Lecture::whereHas('chapters.course',function ($query){
            $query->where('id',$_GET['course_id']);
        })->with('materials')->get();
        $data['course']=Course::find($_GET['course_id']);
        $data['activeContent']='active';
        return view('student.courses.content.index',$data);
    }
}
