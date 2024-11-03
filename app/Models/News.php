<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = 'news';
    protected $fillable = ['title','description','admin_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id','user_id');
    }
    public function college()
    {
        return $this->belongsTo(Department::class);
    }
}
