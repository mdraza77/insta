<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $fillable = ['user_id', 'image_url', 'caption', 'location', 'is_reel'];

    protected $with = ['user'];

    protected $appends = ['likes_count', 'comments_count'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function isSavedBy($user)
    {
        if (!$user) return false;
        return DB::table('saves')->where('user_id', $user->id)->where('post_id', $this->id)->exists();
    }
}
