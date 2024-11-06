<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcement';
    protected $fillable = ['instructor_id', 'text', 'title', 'status', 'choices'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
