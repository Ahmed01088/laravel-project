<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\Post;
use App\Models\Admin;
use App\Models\Notification;


class StudentAffair extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'phone_no',
        'image',
        'admin_id',
        'fcm_token',
        'password',
        'responsible_level',
        'date_added'
    ];
    protected $hidden = [
        'password',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
    public function commentReply()
    {
        return $this->hasMany(CommentReply::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class);
    }
    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
    public function reaction()
    {
        return $this->hasMany(Reaction::class);
    }
}
