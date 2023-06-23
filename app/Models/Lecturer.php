<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Course;
use App\Models\Post;
use App\Models\Quiz;
use App\Models\Classroom;
use App\Models\Realtimes;
use App\Models\Notification;




class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'image',
        'fcm_token',
        'course_id',
        'phone_no',
        'password',
        'department_id',
    ];
    protected $hidden = [
        'password',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentReplies()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
    public function classroom()
    {
        return $this->hasMany(Classroom::class);
    }
    public function realtime()
    {
        return $this->hasMany(Realtimes::class);
    }
    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
}
