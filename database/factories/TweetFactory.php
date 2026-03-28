<?php

namespace Database\Factories;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tweet>
 */
class TweetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => Str::limit(fake()->realText(180), 280, ''),
        ];
    }
}