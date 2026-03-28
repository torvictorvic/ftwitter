<?php

use App\Models\Tweet;
use App\Models\User;

test('guests cannot delete tweets', function () {
    $tweet = Tweet::factory()->create();

    $this->delete(route('tweets.destroy', $tweet))
        ->assertRedirect(route('login'));
});

test('owners can delete their own tweet', function () {
    $user = User::factory()->create();
    $tweet = Tweet::factory()->for($user)->create();

    $this->actingAs($user)
        ->delete(route('tweets.destroy', $tweet))
        ->assertRedirect();

    $this->assertDatabaseMissing('tweets', [
        'id' => $tweet->id,
    ]);
});

test('users cannot delete tweets from other users', function () {
    $owner = User::factory()->create();
    $intruder = User::factory()->create();
    $tweet = Tweet::factory()->for($owner)->create();

    $this->actingAs($intruder)
        ->delete(route('tweets.destroy', $tweet))
        ->assertForbidden();

    $this->assertDatabaseHas('tweets', [
        'id' => $tweet->id,
    ]);
});
