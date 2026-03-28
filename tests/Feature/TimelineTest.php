<?php

use App\Models\Tweet;
use App\Models\User;
use Carbon\CarbonImmutable;
use Inertia\Testing\AssertableInertia as Assert;

test('timeline shows authenticated user tweets and followed users tweets ordered by newest first', function () {
    $viewer = User::factory()->create();
    $followed = User::factory()->create();
    $stranger = User::factory()->create();

    $viewer->following()->attach($followed->id);

    $ownTweet = Tweet::factory()->for($viewer)->create([
        'body' => 'Own tweet',
        'created_at' => CarbonImmutable::parse('2026-03-01 10:00:00'),
        'updated_at' => CarbonImmutable::parse('2026-03-01 10:00:00'),
    ]);

    $followedTweet = Tweet::factory()->for($followed)->create([
        'body' => 'Newest followed tweet',
        'created_at' => CarbonImmutable::parse('2026-03-01 11:00:00'),
        'updated_at' => CarbonImmutable::parse('2026-03-01 11:00:00'),
    ]);

    Tweet::factory()->for($stranger)->create([
        'body' => 'Stranger tweet',
        'created_at' => CarbonImmutable::parse('2026-03-01 12:00:00'),
        'updated_at' => CarbonImmutable::parse('2026-03-01 12:00:00'),
    ]);

    $followedTweet->likes()->create(['user_id' => $viewer->id]);

    $this->actingAs($viewer)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('tweets.data', 2)
            ->where('tweets.data.0.body', 'Newest followed tweet')
            ->where('tweets.data.0.author.username', $followed->username)
            ->where('tweets.data.0.is_liked', true)
            ->where('tweets.data.0.likes_count', 1)
            ->where('tweets.data.1.body', 'Own tweet')
            ->where('tweets.data.1.can_delete', true)
        );
});
