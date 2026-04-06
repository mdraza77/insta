<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $authUser = auth()->user();

        // Prevent self-following
        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself.'], 403);
        }

        if ($authUser->isFollowing($user)) {
            // Unfollow logic
            $authUser->following()->detach($user->id);
            $status = 'follow';
        } else {
            // Follow logic (Default status is accepted as per your migration)
            $authUser->following()->attach($user->id, ['status' => 'accepted']);
            $status = 'following';
        }

        return response()->json([
            'status' => $status,
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ]);
    }
}
