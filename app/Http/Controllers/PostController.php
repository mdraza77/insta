<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

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
            'media.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 5MB Max
        ]);

        // 1. Post entry create karo
        $post = auth()->user()->posts()->create([
            'caption' => $request->caption,
            'location' => $request->location,
        ]);

        // 2. Multiple Media upload aur link karo
        foreach ($request->file('media') as $index => $file) {
            $path = $file->store('posts', 'public');

            $post->media()->create([
                'media_url' => $path,
                'media_type' => 'image',
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
}
