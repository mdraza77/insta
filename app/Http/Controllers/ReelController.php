<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ReelController extends Controller
{
    /**
     * Display the reels page with all reels.
     * @param Request $request
     */
    public function index(Request $request)
    {
        // Get all reels (posts marked as is_reel = true) ordered by latest
        $reels = Post::where('is_reel', true)
            ->where('user_id', '!=', auth()->id())
            ->with(['user', 'media', 'likes'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();

        // Check if coming from feed click (reel parameter)
        $activeReelId = $request->query('reel');

        return view('reels.index', compact('reels', 'activeReelId'));
    }
}
