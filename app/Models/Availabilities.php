<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availabilities extends Model
{
    use HasFactory;
    protected $table='availabilities';
    protected $fillable=['instructor_id','days','start_time','end_time'];

    public function instructor()
    {
       return $this->belongsTo(Instructor::class);
    }

    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
