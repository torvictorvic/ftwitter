<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    if (! method_exists(new User, 'hasEnabledTwoFactorAuthentication')) {
        $this->markTestSkipped('User model does not implement two-factor helpers.');
    }
});

test('two factor challenge redirects to login when there is no pending login', function () {
    $this->get(route('two-factor.login'))
        ->assertRedirect(route('login'));
});

test('two factor challenge can be rendered after password authentication', function () {
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->get(route('two-factor.login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/TwoFactorChallenge')
        );
});
