<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\StoryMedia;
use App\Models\StoryView;
use App\Models\User;

class StoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20000',
        ]);

        // 1. Create Story entry
        $story = Story::create([
            'user_id' => auth()->id(),
            'expires_at' => now()->addHours(24), // 24 ghante baad expire
        ]);

        // 2. Handle Media Upload
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('stories', 'public');
            $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

            StoryMedia::create([
                'story_id' => $story->id,
                'media_url' => $path,
                'media_type' => $type,
            ]);
        }

        return back()->with('success', 'Story uploaded!');
    }
}
