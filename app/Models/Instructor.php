<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Instructor extends Model
{
    use HasFactory;
    protected $table = 'instructor';
     protected $fillable=['first_name','last_name','user_id','department_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function availabilities()
    {
        return $this->hasMany(Availabilities::class, 'instructor_id');
    }
    public function college()
    {
        return $this->belongsTo(Department::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_instructor')
            ->withPivot('days', 'start_time', 'end_time');
    }
}
