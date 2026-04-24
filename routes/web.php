<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\StoryController;
use App\Models\Post;
use App\Models\User;

// resources/routes/web.php

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Agar login nahi hai, toh login page par bhej do
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/reels', [ReelController::class, 'index'])->middleware(['auth'])->name('reels.index');

Route::middleware('auth')->group(function () {
    // Ye fixed routes hain, inhein upar rehne do
    Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile.edit'); // Path change kiya safety ke liye
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->middleware('auth')
        ->name('profile.photo.update');
    Route::patch('/profile/privacy', [ProfileController::class, 'updatePrivacy'])
        ->middleware('auth')
        ->name('profile.privacy.update');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/user/{user}/follow', [FollowController::class, 'toggleFollow'])->name('user.follow');

    Route::post('/posts/{post}/save', [PostController::class, 'toggleSave'])->name('posts.save');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');

    // Pehle specific chat wala route
    Route::get('/messages/{username}', [MessageController::class, 'chat'])->name('messages.chat');

    // Phir general index wala route
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    // Send Message - Message bhejni ki API
    Route::post('/messages/{conversation}/send', [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/share', [MessageController::class, 'share'])->name('messages.share');
    // Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
});

require __DIR__ . '/auth.php';

Route::get('/{username}', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');
