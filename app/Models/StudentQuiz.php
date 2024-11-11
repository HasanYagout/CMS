<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    use HasFactory;

    protected $table = 'students_quiz';
    protected $fillable = ['instructor_quiz_id', 'student_id', 'grade'];

    public function instructorQuiz()
    {
        return $this->belongsTo(InstructorQuiz::class);
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
