<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $table = 'quiz_questions';
    protected $fillable = ['text','type','options','correct_answer'];
    public function quiz()
    {
        return $this->belongsTo(InstructorQuiz::class);
    }
}
