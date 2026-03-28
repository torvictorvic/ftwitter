<?php

use App\Http\Middleware\HandleAppearance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

test('appearance middleware shares appearance from the cookie', function () {
    $request = Request::create('/', 'GET', [], ['appearance' => 'dark']);
    $request->setLaravelSession(app('session.store'));

    $response = app(HandleAppearance::class)->handle($request, fn () => response('ok'));

    expect($response->getStatusCode())->toBe(200);
    expect(View::shared('appearance'))->toBe('dark');
});

test('appearance middleware defaults to system when cookie is missing', function () {
    $request = Request::create('/', 'GET');
    $request->setLaravelSession(app('session.store'));

    app(HandleAppearance::class)->handle($request, fn () => response('ok'));

    expect(View::shared('appearance'))->toBe('system');
});
