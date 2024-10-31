<?php

namespace App\Models;

use App\Http\Middleware\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;
    protected   $table = 'students_assignments';

    public function instructorAssignment()
    {
        return $this->belongsTo(InstructorAssignments::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
