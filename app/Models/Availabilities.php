<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availabilities extends Model
{
    use HasFactory;
    protected $table='availabilities';
    protected $fillable=['instructor_id','days','start_time','end_time','course_id','status'];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id','user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
