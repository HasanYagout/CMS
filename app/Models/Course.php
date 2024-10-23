<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $fillable = ['name','image','slug','instructor_id','availability_id','semester_id','description','status'];

    public function availability()
    {
        return $this->belongsTo(Availabilities::class);
    }
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function chapter()
    {
        return $this->hasMany(Chapter::class);
    }
    public function instructor_assignment()
    {
        return $this->hasMany(InstructorAssignments::class);
    }
}
