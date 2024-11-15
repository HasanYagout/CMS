<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;

    protected $table = 'students_assignments';
    protected $fillable = ['student_id', 'instructor_assignments_id', 'comment', 'path', 'grade'];

    public function instructorAssignment()
    {
        return $this->belongsTo(InstructorAssignments::class);
    }

    public function assignment()
    {
        return $this->belongsTo(InstructorAssignments::class, 'instructor_assignments_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
