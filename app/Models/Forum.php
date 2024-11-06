<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    use HasFactory;

    protected $table = 'forum';
    protected $fillable = ['course_id', 'title', 'status', 'description', 'instructor_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
