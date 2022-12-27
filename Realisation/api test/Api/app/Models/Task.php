<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function students(){
        $this->belongsToMany(Student::class);
    }
    public function student_brief(){
        $this->belongsTo(Student_brief::class);
    }
}
