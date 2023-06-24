<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'student_id',
        'lecturer_id',
        'student_affairs_id',
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function studentAffairs()
    {
        return $this->belongsTo(StudentAffair::class);
    }
}
