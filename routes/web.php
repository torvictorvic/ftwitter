<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TweetController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::post('/tweets', [TweetController::class, 'store'])->name('tweets.store');
    Route::delete('/tweets/{tweet}', [TweetController::class, 'destroy'])->name('tweets.destroy');

    Route::post('/tweets/{tweet}/likes', [LikeController::class, 'store'])->name('tweets.likes.store');
    Route::delete('/tweets/{tweet}/likes', [LikeController::class, 'destroy'])->name('tweets.likes.destroy');

    Route::get('/users/search', SearchController::class)->name('users.search');
    Route::get('/users/{user:username}', [ProfileController::class, 'show'])->name('users.show');

    Route::post('/users/{user:username}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user:username}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');
});

require __DIR__.'/settings.php';
