<?php

use App\Models\Like;
use App\Models\Tweet;
use App\Models\User;

test('like belongs to a user and a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();

    $like = Like::create([
        'user_id' => $user->id,
        'tweet_id' => $tweet->id,
    ]);

    expect($like->user->is($user))->toBeTrue();
    expect($like->tweet->is($tweet))->toBeTrue();
});
