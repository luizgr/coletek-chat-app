<?php

namespace Tests\Feature\Chat;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetGuildChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_channels_can_be_retrieved(): void
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
            ->get("/api/guilds/{$guild->id}/channels/{$channel->id}");

        $this->assertEquals($channel->id, $response['id']);
    }
}
