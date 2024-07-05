<?php

namespace Tests\Feature\Chat;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_can_be_retrieved(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertNotNull($response['access_token']);
    }

    public function test_token_can_not_be_retrieved_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }
}
