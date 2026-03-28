<?php

namespace Database\Seeders;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Demo User',
            'username' => 'demouser',
            'bio' => 'Cuenta demo para evaluacion.',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $users = User::factory(9)->create();
        $allUsers = $users->prepend($demo);

        $allUsers->each(function (User $user): void {
            Tweet::factory(rand(4, 8))
                ->for($user)
                ->create();
        });

        $allUsers->each(function (User $user) use ($allUsers): void {
            $targets = $allUsers
                ->where('id', '!=', $user->id)
                ->shuffle()
                ->take(rand(2, 4));

            $targets->each(function (User $target) use ($user): void {
                $user->following()->syncWithoutDetaching([$target->id]);
            });
        });

        $allTweets = Tweet::all();

        $allUsers->each(function (User $user) use ($allTweets): void {
            $allTweets
                ->where('user_id', '!=', $user->id)
                ->shuffle()
                ->take(rand(5, 12))
                ->each(function (Tweet $tweet) use ($user): void {
                    $tweet->likes()->firstOrCreate([
                        'user_id' => $user->id,
                    ]);
                });
        });
    }
}