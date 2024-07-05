<?php

namespace Tests\Feature\Chat;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateMessageChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_can_be_created(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $channel = $guild->channels()->create([
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);

        $response = $this
            ->actingAs($user)
            ->post("/api/guilds/{$guild->id}/channels/{$channel->id}/messages", [
                'content' => 'Test Message',
            ]);

        $response->assertStatus(201);
    }
}
