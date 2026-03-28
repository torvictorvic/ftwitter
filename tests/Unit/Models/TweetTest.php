<?php

use App\Models\Tweet;
use App\Models\User;

test('tweet belongs to a user', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->for($user)->create();

    expect($tweet->user->is($user))->toBeTrue();
});

test('tweet has many likes', function () {
    $tweet = Tweet::factory()->create();
    $user = User::factory()->create();

    $tweet->likes()->create(['user_id' => $user->id]);

    expect($tweet->likes)->toHaveCount(1);
    expect($tweet->likes->first()->user_id)->toBe($user->id);
});
