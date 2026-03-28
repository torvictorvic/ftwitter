<?php

use App\Models\Tweet;
use App\Models\User;

test('user uses username as route key name', function () {
    expect((new User)->getRouteKeyName())->toBe('username');
});

test('user builds initials from the first two name parts', function () {
    $user = User::factory()->make(['name' => 'Victor Manuel Suarez Torres']);

    expect($user->initials())->toBe('VM');
});

test('user knows whether it follows another user', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $alice->following()->attach($bob->id);

    expect($alice->isFollowing($bob))->toBeTrue();
    expect($bob->isFollowing($alice))->toBeFalse();
});

test('user knows whether it has liked a tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->create();

    $tweet->likes()->create(['user_id' => $user->id]);

    expect($user->hasLiked($tweet))->toBeTrue();
});

test('followers and following relationships point to the correct users', function () {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    $alice->following()->attach($bob->id);

    expect($alice->following->pluck('id')->all())->toBe([$bob->id]);
    expect($bob->followers->pluck('id')->all())->toBe([$alice->id]);
});
