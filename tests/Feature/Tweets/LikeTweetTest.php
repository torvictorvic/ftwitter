<?php

use App\Models\Tweet;
use App\Models\User;

test('guests cannot like tweets', function () {
    $tweet = Tweet::factory()->create();

    $this->post(route('tweets.likes.store', $tweet))
        ->assertRedirect(route('login'));
});

test('authenticated users can like a tweet', function () {
    $viewer = User::factory()->create();
    $tweet = Tweet::factory()->create();

    $this->actingAs($viewer)
        ->post(route('tweets.likes.store', $tweet))
        ->assertRedirect();

    $this->assertDatabaseHas('likes', [
        'user_id' => $viewer->id,
        'tweet_id' => $tweet->id,
    ]);
});

test('liking a tweet twice does not create duplicate likes', function () {
    $viewer = User::factory()->create();
    $tweet = Tweet::factory()->create();

    $this->actingAs($viewer)->post(route('tweets.likes.store', $tweet));
    $this->actingAs($viewer)->post(route('tweets.likes.store', $tweet));

    expect($tweet->likes()->where('user_id', $viewer->id)->count())->toBe(1);
});

test('authenticated users can unlike a tweet', function () {
    $viewer = User::factory()->create();
    $tweet = Tweet::factory()->create();

    $tweet->likes()->create(['user_id' => $viewer->id]);

    $this->actingAs($viewer)
        ->delete(route('tweets.likes.destroy', $tweet))
        ->assertRedirect();

    $this->assertDatabaseMissing('likes', [
        'user_id' => $viewer->id,
        'tweet_id' => $tweet->id,
    ]);
});
