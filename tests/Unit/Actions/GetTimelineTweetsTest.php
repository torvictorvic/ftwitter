<?php

use App\Actions\Timeline\GetTimelineTweets;
use App\Models\Tweet;
use App\Models\User;
use Carbon\CarbonImmutable;

test('timeline action returns own tweets and followed users tweets with like metadata', function () {
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
        'body' => 'Followed tweet',
        'created_at' => CarbonImmutable::parse('2026-03-01 11:00:00'),
        'updated_at' => CarbonImmutable::parse('2026-03-01 11:00:00'),
    ]);

    Tweet::factory()->for($stranger)->create([
        'body' => 'Stranger tweet',
        'created_at' => CarbonImmutable::parse('2026-03-01 12:00:00'),
        'updated_at' => CarbonImmutable::parse('2026-03-01 12:00:00'),
    ]);

    $followedTweet->likes()->create(['user_id' => $viewer->id]);

    $paginator = app(GetTimelineTweets::class)->handle($viewer, 10);
    $items = collect($paginator->items());

    expect($items)->toHaveCount(2);
    expect($items->pluck('body')->all())->toBe(['Followed tweet', 'Own tweet']);
    expect($items->first()->likes_count)->toBe(1);
    expect((bool) $items->first()->is_liked)->toBeTrue();
    expect($items->last()->id)->toBe($ownTweet->id);
});
