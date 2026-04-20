<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryMedia extends Model
{
    protected $guarded = [];
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
