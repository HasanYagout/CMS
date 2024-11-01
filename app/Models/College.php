<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;
    protected $table = 'college';
    protected $fillable=['name'];

    public function student()
    {
        return $this->hasMany(Student::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
