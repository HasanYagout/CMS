<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $table = 'academic_years';
    protected $fillable=['name'];

    public function course()
    {
        return $this->hasMany(Course::class);
    }
}
