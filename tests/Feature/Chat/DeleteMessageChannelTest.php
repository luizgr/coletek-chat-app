<?php

namespace Tests\Feature\Chat;

use App\Models\Guild;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteMessageChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_messages_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $channel = $guild->channels()->create([
            'name' => 'Test Channel',
            'description' => 'Test Description',
        ]);

        $message = Message::create([
            'channel_id' => $channel->id,
            'user_id' => $user->id,
            'content' => 'Test Message',
        ]);

        $this
            ->actingAs($user)
            ->delete("/api/guilds/{$guild->id}/channels/{$channel->id}/messages/{$message->id}");

        $this->assertNull($message->fresh());
    }
}
