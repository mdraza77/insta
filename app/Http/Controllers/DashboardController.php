<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Saare posts load karo with user and media
        $posts = Post::with(['user', 'media', 'comments.user'])
            ->latest()
            ->get();
        return view('dashboard', compact('posts'));
    }
}
