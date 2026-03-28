<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

test('login screen can be rendered', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/Login')
            ->where('canRegister', true)
            ->where('canResetPassword', true)
        );
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('home', absolute: false));
});

test('users with two factor enabled are redirected to two factor challenge when supported by the user model', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    if (! method_exists(new User, 'hasEnabledTwoFactorAuthentication')) {
        $this->markTestSkipped('User model does not implement two-factor helpers.');
    }

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $response->assertSessionHas('login.id', $user->id);
    $this->assertGuest();
});

test('users cannot authenticate with an invalid password', function () {
    $user = User::factory()->create();

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('home', absolute: false));
});

test('users are rate limited after too many login attempts', function () {
    $user = User::factory()->create();

    $throttleKey = Str::transliterate(Str::lower($user->email).'|127.0.0.1');

    RateLimiter::clear($throttleKey);

    foreach (range(1, 5) as $_) {
        RateLimiter::hit($throttleKey, 60);
    }

    $response = $this->from(route('login'))->post(route('login.store'), [
        Fortify::username() => $user->email,
        'password' => 'wrong-password',
    ]);

    expect(RateLimiter::tooManyAttempts($throttleKey, 5))->toBeTrue();

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors(Fortify::username());
    $this->assertGuest();
});
