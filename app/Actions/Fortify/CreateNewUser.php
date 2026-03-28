<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Rules\Password;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:30', Rule::unique(User::class)],
            'bio' => ['nullable', 'string', 'max:160'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'username' => Str::lower($input['username']),
            'bio' => $input['bio'] ?? null,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
    }
}