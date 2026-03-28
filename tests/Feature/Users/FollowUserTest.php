<?php

use App\Models\User;

test('guests cannot follow users', function () {
    $target = User::factory()->create();

    $this->post(route('users.follow', $target))
        ->assertRedirect(route('login'));
});

test('a user can follow another user', function () {
    $viewer = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($viewer)
        ->post(route('users.follow', $target))
        ->assertRedirect();

    $this->assertDatabaseHas('follows', [
        'follower_id' => $viewer->id,
        'followed_id' => $target->id,
    ]);
});

test('a user cannot follow themselves', function () {
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->post(route('users.follow', $viewer))
        ->assertStatus(422);
});

test('following twice does not create duplicate rows', function () {
    $viewer = User::factory()->create();
    $target = User::factory()->create();

    $this->actingAs($viewer)->post(route('users.follow', $target));
    $this->actingAs($viewer)->post(route('users.follow', $target));

    expect($viewer->following()->where('users.id', $target->id)->count())->toBe(1);
});

test('a user can unfollow another user', function () {
    $viewer = User::factory()->create();
    $target = User::factory()->create();

    $viewer->following()->attach($target->id);

    $this->actingAs($viewer)
        ->delete(route('users.unfollow', $target))
        ->assertRedirect();

    $this->assertDatabaseMissing('follows', [
        'follower_id' => $viewer->id,
        'followed_id' => $target->id,
    ]);
});
