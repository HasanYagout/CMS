<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorAssignments extends Model
{
    use HasFactory;
    protected $table = 'instructor_assignments';
    protected $fillable=['course_id','status','instructor_id','lecture_id','title','description','grade','due_date'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);

    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function submittedAssignments()
    {
        return $this->hasMany(StudentAssignment::class);
    }
}
