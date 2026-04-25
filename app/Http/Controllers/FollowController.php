<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $authUser = auth()->user();

        // 1. Prevent self-following
        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'You cannot follow yourself.'], 403);
        }

        try {
            if ($authUser->isFollowing($user)) {
                // 2. Unfollow Logic
                $authUser->following()->detach($user->id);
                $status = 'follow';
            } else {
                // 3. Follow Logic
                // syncWithoutDetaching ensures no duplicate entries are created
                $authUser->following()->syncWithoutDetaching([
                    $user->id => ['status' => 'accepted']
                ]);

                // Task: Yahan Notification trigger kar sakte ho
                // Notification::send($user, new NewFollower($authUser));

                $status = 'following';
            }

            return response()->json([
                'status' => $status,
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function removeFollower(User $user)
    {
        // Auth user ki followers list se us user ko hata do
        auth()->user()->followers()->detach($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Follower removed'
        ]);
    }
}
