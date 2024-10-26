<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = ['name','start_date','end_date','image','lectures','hours','slug','instructor_id','availability_id','semester_id','description','status'];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function availability()
    {
        return $this->hasOne(Availabilities::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function chapter()
    {
        return $this->hasMany(Chapter::class);
    }
    public function instructor_assignment()
    {
        return $this->hasMany(InstructorAssignments::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

}
