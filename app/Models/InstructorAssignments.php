<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorAssignments extends Model
{
    use HasFactory;
    protected $table = 'instructor_assignments';
    protected $fillable=['course_id','instructor_id','chapter_id','title','description','marks','due_date'];

    public function course()
    {
        return $this->belongsTo(Course::class);

    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
