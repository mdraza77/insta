<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // USERNAME 14-DAY RULE
        if (
            $request->username !== $user->username &&
            $user->username_updated_at &&
            $user->username_updated_at->diffInDays(now()) < 14
        ) {
            return back()->withErrors([
                'username' => 'You can change your username only once in 14 days'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:150',
            'website' => 'nullable|url|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        Log::info('Profile update request validated successfully.');

        // IMAGE UPLOAD
        if ($request->hasFile('photo')) {

            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('photo')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        // USERNAME TIMESTAMP UPDATE
        if ($request->username !== $user->username) {
            $user->username_updated_at = now();
        }

        //UPDATE DATA
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'bio' => $request->bio,
            'website' => $request->website,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        Log::info('User profile updated successfully.', ['user_id' => $user->id]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show($username)
    {
        $user = User::where('username', $username)
            ->withCount(['posts', 'followers', 'following'])
            ->firstOrFail();

        // Saari posts load karo media ke saath
        $allPosts = $user->posts()->with('media')->latest()->get();

        // Posts aur Reels filter by media type
        $posts = $allPosts->filter(function ($post) {
            return $post->media->first()->media_type !== 'video';
        });

        $reels = $allPosts->filter(function ($post) {
            return $post->media->first()->media_type === 'video';
        });

        // Saved posts only for the profile owner
        $savedPosts = collect();
        if (auth()->id() === $user->id) {
            $savedPosts = $user->savedPosts()->with('media')->latest()->get();
        }

        return view('profile.show', compact('user', 'posts', 'reels', 'savedPosts'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        $user = auth()->user();

        // Old Image Delete
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // file store in local storage
        $path = $request->file('photo')->store('profiles', 'public');

        // save path in DB
        $user->profile_picture = $path;
        $user->save();

        return back();
    }

    public function updatePrivacy(Request $request)
    {
        // dd($request->all());
        $user = $request->user();

        $user->update([
            'is_private' => $request->has('is_private'),
            'show_activity' => $request->has('show_activity'),
            'read_receipts' => $request->has('read_receipts'),
            'restrict_mentions' => $request->has('restrict_mentions'),
        ]);

        return back()->with('status', 'privacy-updated');
    }
}
