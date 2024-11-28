<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluate extends Model
{
    use HasFactory;

    protected $table = 'evaluates';
    protected $fillable = ['student_id', 'course_id', 'instructor_id', 'description'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
