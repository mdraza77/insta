<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    // Kaun-kaun se users is chat mein hain
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('read_at', 'is_muted')->withTimestamps();
    }

    // Is chat ke saare messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Last message ka direct link (Efficiency ke liye)
    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    /**
     * Helper: Chat list mein dusre bande ki info dikhane ke liye
     */
    public function getReceiver()
    {
        return $this->users->where('id', '!=', auth()->id())->first();
    }
}
