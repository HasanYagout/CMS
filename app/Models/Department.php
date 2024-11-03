<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';
    protected $fillable=['name','status'];

    public function student()
    {
        return $this->hasMany(Student::class);
    }
    public function instructor()
    {
        return $this->hasMany(Instructor::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
