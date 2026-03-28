<?php

use App\Models\Tweet;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

test('database seeder creates a realistic social graph', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::count())->toBe(10);
    expect(User::where('email', 'demo@example.com')->exists())->toBeTrue();
    expect(Tweet::count())->toBeGreaterThanOrEqual(40);
    expect(DB::table('follows')->count())->toBeGreaterThan(0);
    expect(DB::table('likes')->count())->toBeGreaterThan(0);
    expect(DB::table('follows')->whereColumn('follower_id', 'followed_id')->count())->toBe(0);
});
