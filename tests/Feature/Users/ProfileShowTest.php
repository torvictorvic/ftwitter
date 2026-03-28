<?php

use App\Models\Tweet;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('a user can view another profile with tweets and relationship counts', function () {
    $viewer = User::factory()->create(['name' => 'Viewer User', 'username' => 'viewer']);
    $target = User::factory()->create(['name' => 'Target User', 'username' => 'target']);
    $follower = User::factory()->create(['name' => 'Follower User', 'username' => 'follower']);
    $followed = User::factory()->create(['name' => 'Followed User', 'username' => 'followed']);

    $viewer->following()->attach($target->id);
    $follower->following()->attach($target->id);
    $target->following()->attach($followed->id);

    $tweet = Tweet::factory()->for($target)->create(['body' => 'Target timeline item']);
    $tweet->likes()->create(['user_id' => $viewer->id]);

    $this->actingAs($viewer)
        ->get(route('users.show', $target))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Show')
            ->where('profileUser.username', 'target')
            ->where('profileUser.is_following', true)
            ->where('profileUser.is_self', false)
            ->where('profileUser.followers_count', 2)
            ->where('profileUser.following_count', 1)
            ->where('tweets.data.0.body', 'Target timeline item')
            ->where('tweets.data.0.is_liked', true)
            ->has('followers', 2)
            ->has('following', 1)
        );
});

test('a user viewing their own profile is marked as self', function () {
    $viewer = User::factory()->create(['username' => 'selfuser']);

    $this->actingAs($viewer)
        ->get(route('users.show', $viewer))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Profile/Show')
            ->where('profileUser.username', 'selfuser')
            ->where('profileUser.is_self', true)
            ->where('profileUser.is_following', false)
        );
});
