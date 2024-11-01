<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function view($id)
    {
        $data['material']=Material::find($id);
        return view('student.courses.materials.view',$data);
    }
}
