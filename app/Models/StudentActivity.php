<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivity extends Model
{
    use HasFactory;

    protected $table = 'student_activities';
    protected $fillable = ['instructor_activity_id', 'student_id', 'selected_option', 'correct', 'grade'];

    public function InstructorActivity()
    {
        return $this->belongsTo(InstructorActivity::class);
    }
}
