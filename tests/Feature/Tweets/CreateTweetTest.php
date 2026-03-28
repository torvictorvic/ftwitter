<?php

use App\Models\Tweet;
use App\Models\User;

test('guests cannot create tweets', function () {
    $this->post(route('tweets.store'), [
        'body' => 'Hello world',
    ])->assertRedirect(route('login'));
});

test('authenticated users can create tweets', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('tweets.store'), [
            'body' => 'My first tweet',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tweets', [
        'user_id' => $user->id,
        'body' => 'My first tweet',
    ]);
});

test('tweet body is trimmed before it is stored', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tweets.store'), [
            'body' => '   Trim me   ',
        ]);

    $this->assertDatabaseHas('tweets', [
        'user_id' => $user->id,
        'body' => 'Trim me',
    ]);
});

test('tweets cannot exceed 280 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tweets.store'), [
            'body' => str_repeat('a', 281),
        ])
        ->assertSessionHasErrors('body');

    expect(Tweet::count())->toBe(0);
});

test('tweets cannot be only whitespace', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('tweets.store'), [
            'body' => "   \n\t   ",
        ])
        ->assertSessionHasErrors('body');

    expect(Tweet::count())->toBe(0);
});
