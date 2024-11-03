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

public function user()
{
return $this->belongsTo(User::class);
}

public function submittedAssignments()
{
return $this->hasMany(StudentAssignment::class,'');
}

public function studentQuizzes()
{
return $this->hasMany(StudentQuiz::class);
}
}
