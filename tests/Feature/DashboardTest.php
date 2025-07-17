<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/chat');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the chat', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/chat');
    $response->assertStatus(200);
});
