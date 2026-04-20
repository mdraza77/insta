<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(StoryMedia::class);
    }

    public function views()
    {
        return $this->hasMany(StoryView::class);
    }

    // Scope to get only active stories
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
