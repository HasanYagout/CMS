<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    protected $table = 'instructor';


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function availabilities()
    {
        return $this->hasMany(Availabilities::class, 'instructor_id');
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
