<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorQuiz extends Model
{
    use HasFactory;
    protected $table = 'instructor_quiz';
    protected $fillable = ['title','course_id','chapter_id'];
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
