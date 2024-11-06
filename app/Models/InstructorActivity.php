<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorActivity extends Model
{
    use HasFactory;

    protected $table = "instructor_activities";
    protected $fillable = ['lecture_id', 'title', 'options', 'correct_answer', 'grade', 'instructor_id', 'due_date', 'status'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function studentActivity()
    {
        return $this->hasMany(StudentActivity::class);
    }
}
