<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    public function college()
    {
        return $this->belongsTo(Department::class);
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function evaluate()
    {
        return $this->hasOne(Evaluate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submittedAssignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function studentQuizzes()
    {
        return $this->hasMany(StudentQuiz::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function assignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }
}
