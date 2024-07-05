<?php

namespace Tests\Feature\Chat;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_can_be_retrieved(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/api/user');

        $response->assertStatus(200);
    }
}
