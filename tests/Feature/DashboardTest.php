<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected to the login page from home', function () {
    $this->get(route('home'))
        ->assertRedirect(route('login'));
});

test('authenticated users can visit the home timeline', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->has('tweets')
        );
});
