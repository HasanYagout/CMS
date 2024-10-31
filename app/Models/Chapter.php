<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $table = 'chapters';
    protected $fillable = ['title','course_id','status'];

    public function course()
    {
       return $this->belongsTo(Course::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function instructor_assignment()
    {
        return $this->hasMany(InstructorAssignments::class);
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }

}
