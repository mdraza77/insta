<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Saare posts load karo with user and media
        $posts = Post::with(['user', 'media', 'comments.user'])
            ->latest()
            ->get();

        $users_with_stories = User::has('stories')->with(['stories.media'])->get();

        return view('dashboard', compact('posts', 'users_with_stories'));
    }
}
