<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Models\User;
use Illuminate\Http\Request;

test('inertia middleware shares app name authenticated user and sidebar open by default', function () {
    $user = User::factory()->create();
    $request = Request::create('/', 'GET');
    $request->setLaravelSession(app('session.store'));
    $request->setUserResolver(fn () => $user);

    $shared = app(HandleInertiaRequests::class)->share($request);

    expect($shared['name'])->toBe(config('app.name'));
    expect($shared['auth']['user']->is($user))->toBeTrue();
    expect($shared['sidebarOpen'])->toBeTrue();
});

test('inertia middleware reads sidebar cookie state', function () {
    $request = Request::create('/', 'GET', [], ['sidebar_state' => 'false']);
    $request->setLaravelSession(app('session.store'));
    $request->setUserResolver(fn () => null);

    $shared = app(HandleInertiaRequests::class)->share($request);

    expect($shared['sidebarOpen'])->toBeFalse();
});
