<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

test('create new user action lowercases username and hashes the password', function () {
    $action = new CreateNewUser;

    $user = $action->create([
        'name' => 'Victor User',
        'username' => 'VictorMST',
        'bio' => 'Building software.',
        'email' => 'victor@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->username)->toBe('victormst');
    expect($user->bio)->toBe('Building software.');
    expect(Hash::check('password', $user->password))->toBeTrue();
});

test('create new user action validates duplicate usernames', function () {
    User::factory()->create(['username' => 'existinguser']);

    $action = new CreateNewUser;

    expect(fn () => $action->create([
        'name' => 'Another User',
        'username' => 'existinguser',
        'bio' => 'Another bio.',
        'email' => 'another@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]))->toThrow(ValidationException::class);
});
