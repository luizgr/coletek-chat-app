<?php

namespace Tests\Feature\Chat;

use App\Models\Channel;
use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuildChannelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_channels_can_be_listed(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $guild->channels()->createMany(
            Channel::factory()->count(3)->make()->toArray()
        );

        $this->actingAs($user);

        $request = $this->get('/api/guilds/' . $guild->id);

        $request->assertJsonCount(3, 'channels');
    }

    public function test_channels_with_invalid_guild_id_cannot_be_listed(): void
    {
        $user = User::factory()->create();

        Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $id = rand(1, 100);

        $this->actingAs($user);

        $request = $this->get('/api/guilds/' . $id);

        $request->assertStatus(404);
    }
}
