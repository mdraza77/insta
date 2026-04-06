<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        $comment->load('user');
        $comment->created_at = $comment->created_at->diffForHumans();

        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]);
    }

    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->get()
            ->map(function ($comment) {
                $comment->created_at = $comment->created_at->diffForHumans();
                $comment->replies->each(function ($reply) {
                    $reply->created_at = $reply->created_at->diffForHumans();
                });
                return $comment;
            });

        return response()->json([
            'success' => true,
            'comments' => $comments,
        ]);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
        ]);
    }
}
