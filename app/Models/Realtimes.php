<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realtimes extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'lecturer_id',
        'quiz_id',
        'classroom_id',
        'is_online',
        'is_quiz_started',
        'is_live'

    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
