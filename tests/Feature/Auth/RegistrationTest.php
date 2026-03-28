<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/Register')
        );
});

test('new users can register with username and bio', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Test User',
        'username' => 'TestUser',
        'bio' => 'Builder of timelines.',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('home', absolute: false));
    $this->assertAuthenticated();

    $user = User::where('email', 'test@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->username)->toBe('testuser');
    expect($user->bio)->toBe('Builder of timelines.');
});

test('registration requires a unique username', function () {
    User::factory()->create(['username' => 'existinguser']);

    $response = $this->from(route('register'))->post(route('register.store'), [
        'name' => 'Another User',
        'username' => 'existinguser',
        'bio' => 'Bio',
        'email' => 'another@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('register'));
    $response->assertSessionHasErrors('username');
});
