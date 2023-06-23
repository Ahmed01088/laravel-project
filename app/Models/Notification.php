<?php

namespace App\Models;

use App\Models\Lecturer;
use App\Models\Post;
use App\Models\Student;
use App\Models\StudentAffair;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'student_affair_id',
        'student_id',
        'lecturer_id',
        'post_id',
        'date',
        'type'
    ];

    public function studentAffair()
    {
        return $this->belongsTo(StudentAffair::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
