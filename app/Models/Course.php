<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $fillable = ['name', 'start_date', 'end_date', 'image', 'lectures', 'hours', 'slug', 'department_id', 'availability_id', 'semester_id', 'description', 'status', 'user_id'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availabilities::class);
    }

    public function availability()
    {
        return $this->hasOne(Availabilities::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function instructor_assignment()
    {
        return $this->hasMany(InstructorAssignments::class);
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class);
    }

    public function quizzes()
    {
        return $this->hasMany(InstructorQuiz::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function announcement()
    {
        return $this->hasMany(Announcement::class);
    }

    public function forum()
    {
        return $this->hasMany(Forum::class);
    }


}
