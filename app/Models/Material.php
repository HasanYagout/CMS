<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $table = 'material';
    protected $fillable = ['lecture_id','type','url','title'];


    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }
}
