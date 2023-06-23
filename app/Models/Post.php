<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\StudentAffair;
use App\Models\Lecturer;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Notification;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'posted_at',
        'likes',
        'number_of_comments',
        'student_id',
        'student_affairs_id',
        'lecturer_id'



    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentAffairs()
    {
        return $this->belongsTo(StudentAffair::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentReply()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
}
