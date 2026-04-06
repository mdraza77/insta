<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Like;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'media' => 'required|array',
            'media.*' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,video/mp4,video/quicktime,video/x-msvideo|max:50000', // 50MB Max for videos
        ]);

        $post = auth()->user()->posts()->create([
            'caption' => $request->caption,
            'location' => $request->location,
        ]);

        foreach ($request->file('media') as $index => $file) {
            $path = $file->store('posts', 'public');

            $mime = $file->getMimeType();
            $type = str_contains($mime, 'video') ? 'video' : 'image';

            $post->media()->create([
                'media_url' => $path,
                'media_type' => $type, // Ab ye dynamic hai (image ya video)
                'order' => $index
            ]);
        }

        return back()->with('success', 'Post upload ho gaya!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function toggleLike(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $status = 'unliked';
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
            $post->increment('likes_count');
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'likes_count' => $post->likes_count
        ]);
    }
}
