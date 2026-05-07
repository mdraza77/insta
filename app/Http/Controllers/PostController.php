<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
        Log::info('Incoming request', $request->all());

        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'media' => 'required|array',
            'media.*' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,video/mp4,video/quicktime,video/x-msvideo|max:50000',
            'tags' => 'nullable|string',
            'is_reel' => 'nullable',
        ]);

        // IMPORTANT FIX
        $isReel = $request->has('is_reel');

        Log::info('isReel value', ['isReel' => $isReel]);

        $files = $request->file('media');
        Log::info('Media count', ['count' => count($files ?? [])]);

        // REEL VALIDATION
        if ($isReel) {

            if (!$files || count($files) !== 1) {
                Log::error('Reel validation failed: multiple files');
                return back()->withErrors(['media' => 'Only one video allowed for reels']);
            }

            $file = $files[0];
            $mime = $file->getMimeType();

            Log::info('Reel file mime', ['mime' => $mime]);

            if (!str_contains($mime, 'video')) {
                Log::error('Reel validation failed: not a video');
                return back()->withErrors(['media' => 'Reels must be a video']);
            }
        }

        // CREATE POST
        $post = auth()->user()->posts()->create([
            'caption' => $request->caption,
            'location' => $request->location,
            'is_reel' => $isReel,
        ]);

        Log::info('Post created', ['post_id' => $post->id]);

        // STORE MEDIA
        foreach ($files as $index => $file) {

            $path = $file->store('posts', 'public');
            $mime = $file->getMimeType();
            $type = str_contains($mime, 'video') ? 'video' : 'image';

            Log::info('Media stored', [
                'path' => $path,
                'type' => $type,
                'order' => $index
            ]);

            $post->media()->create([
                'media_url' => $path,
                'media_type' => $type,
                'order' => $index
            ]);
        }

        // TAGS
        if ($request->filled('tags')) {

            $tagNames = explode(',', $request->tags);
            $tagIds = [];

            foreach ($tagNames as $tag) {
                $tag = trim(strtolower($tag));

                if (!$tag) continue;

                $tagModel = Tag::firstOrCreate([
                    'slug' => Str::slug($tag)
                ], [
                    'name' => $tag
                ]);

                Log::info('Tag processed', [
                    'name' => $tag,
                    'id' => $tagModel->id
                ]);

                $tagIds[] = $tagModel->id;
            }

            $post->tags()->sync(
                collect($tagIds)->mapWithKeys(function ($id) {
                    return [
                        $id => [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ];
                })->toArray()
            );
        }

        Log::info('Post creation completed');

        return back()->with('success', 'Post created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // $post->load(['user', 'media', 'tags', 'likes', 'comments.user']);

        // return view('posts.show', compact('post'));
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

    public function toggleSave(Post $post)
    {
        $user = auth()->user();

        // Check if already saved
        if ($user->savedPosts()->where('post_id', $post->id)->exists()) {
            $user->savedPosts()->detach($post->id);
            $status = 'unsaved';
        } else {
            $user->savedPosts()->attach($post->id);
            $status = 'saved';
        }

        return response()->json([
            'status' => $status,
            'message' => $status == 'saved' ? 'Post saved to collection' : 'Removed from saves'
        ]);
    }
}
