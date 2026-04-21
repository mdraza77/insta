<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'media', 'comments.user'])
            ->latest()
            ->get();

        $users_with_stories = User::has('stories')->with(['stories' => function ($q) {
            $q->where('expires_at', '>', now());
        }, 'stories.media', 'stories.user'])->get();

        $users_with_stories->each(function ($user) {
            $user->stories->each(function ($story) {
                $story->time_ago = $story->created_at->diffForHumans(null, true); // Output: 2h, 5m, etc.
            });
        });

        return view('dashboard', compact('posts', 'users_with_stories'));
    }
}
