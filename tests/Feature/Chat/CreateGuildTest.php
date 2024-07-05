<?php

namespace Tests\Feature\Chat;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_guilds_can_be_created(): void
    {
        $request = $this
            ->actingAs(User::factory()->create())
            ->post('/api/guilds', [
                'name' => 'Test Guild',
                'description' => 'Test Description',
            ]);

        $request->assertStatus(201);
    }

    public function test_guilds_require_a_name(): void
    {
        $request = $this
            ->actingAs(User::factory()->create())
            ->post('/api/guilds', [
                'description' => 'Test Description',
            ]);

        $request->assertSessionHasErrors('name');
    }
}
