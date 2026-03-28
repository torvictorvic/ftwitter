<?php

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

test('reset user password action updates the stored password hash', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    $action->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

test('reset user password action requires password confirmation', function () {
    $user = User::factory()->create();
    $action = new ResetUserPassword;

    expect(fn () => $action->reset($user, [
        'password' => 'new-password',
        'password_confirmation' => 'different-password',
    ]))->toThrow(ValidationException::class);
});
