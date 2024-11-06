<?php

namespace App\Models;

use App\Http\Controllers\Student\AssignmentController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Lecture extends Model
{
    use HasFactory;
    protected $table = 'lectures';
    protected $fillable = ['chapter_id','title','description','zoom_link','start_date','end_date','status'];



    public function chapters()
    {
        return $this->belongsTo(Chapter::class,'chapter_id');
    }
    //student dashboard used
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function assignments()
    {
        return $this->hasMany(InstructorAssignments::class);
    }

    public function quizzes()
    {
        return $this->hasMany(InstructorQuiz::class);
    }
    public function activities()
    {
        return $this->hasMany(InstructorActivity::class);
    }


}
