<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuiz extends Model
{
    use HasFactory;
    protected $table = 'students_quiz';

    public function instructorQuiz()
    {
        return $this->belongsTo(InstructorQuiz::class);
    }
}
