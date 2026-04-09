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
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->user()->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'bio' => 'nullable|string|max:150',
            'website' => 'nullable|url|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
        ]);

        Log::info('Profile update request validated successfully.');

        $user = $request->user();

        // 🔥 IMAGE UPLOAD
        if ($request->hasFile('photo')) {

            // old delete
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('photo')->store('profiles', 'public');

            $user->profile_picture = $path;
        }

        // 🔥 UPDATE DATA
        $user->update(['name' => $request->name, 'username' => $request->username, 'email' => $request->email, 'bio' => $request->bio, 'website' => $request->website, 'gender' => $request->gender, 'phone' => $request->phone,]);

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
        // User ko uske username se dhoondo
        $user = User::where('username', $username)
            ->withCount(['posts', 'followers', 'following'])
            ->firstOrFail();

        // Us user ki saari posts fetch karo
        $posts = $user->posts()->with('media')->latest()->get();

        return view('profile.show', compact('user', 'posts'));
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
}
