<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Models\Post;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');

    // Comment routes
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/user/{user}/follow', [FollowController::class, 'toggleFollow'])->name('user.follow');
});

require __DIR__ . '/auth.php';
