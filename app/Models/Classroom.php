<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Quiz;


class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lecturer_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
    public function realtime()
    {
        return $this->hasMany(Realtime::class);
    }
}
