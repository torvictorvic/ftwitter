<?php

use App\Models\User;

test('settings route redirects to profile settings', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/settings')
        ->assertRedirect('/settings/profile');
});
