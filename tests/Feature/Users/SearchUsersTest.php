<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('users can search by name or username and followed users are flagged', function () {
    $viewer = User::factory()->create(['name' => 'Viewer User', 'username' => 'viewer']);
    $john = User::factory()->create(['name' => 'John Carter', 'username' => 'johncarter']);
    $anna = User::factory()->create(['name' => 'Anna Smith', 'username' => 'annasmith']);

    $viewer->following()->attach($john->id);

    $this->actingAs($viewer)
        ->get(route('users.search', ['q' => '  john  ']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Search/Index')
            ->where('filters.q', 'john')
            ->has('users.data', 1)
            ->where('users.data.0.username', 'johncarter')
            ->where('users.data.0.is_following', true)
        );

    expect($anna->username)->toBe('annasmith');
});

test('search results exclude the authenticated user', function () {
    $viewer = User::factory()->create(['name' => 'Viewer User', 'username' => 'viewer']);
    $other = User::factory()->create(['name' => 'Viewer Friend', 'username' => 'viewerfriend']);

    $this->actingAs($viewer)
        ->get(route('users.search', ['q' => 'viewer']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Search/Index')
            ->has('users.data', 1)
            ->where('users.data.0.username', 'viewerfriend')
        );

    expect(User::count())->toBe(2);
    expect($other->username)->toBe('viewerfriend');
});
