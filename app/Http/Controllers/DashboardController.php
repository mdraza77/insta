<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Saare posts load karo with user and media
        $posts = Post::with(['user', 'media', 'comments.user'])->latest()->get();

        // 2. Suggestions fetch karo (Wo users jinhe main follow nahi kar raha)
        // $suggestions = User::where('id', '!=', auth()->id())
        //     ->whereDoesntHave('followers', function ($query) {
        //         $query->where('follower_id', auth()->id());
        //     })
        //     ->limit(5)
        //     ->get();
        // dd($suggestions);
        // 3. Dono variables ko view mein bhejo
        return view('dashboard', compact('posts'));
    }
}
