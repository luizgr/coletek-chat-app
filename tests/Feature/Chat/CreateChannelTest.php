<?php

namespace Tests\Feature\Chat;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_channels_can_be_created(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $request = $this
            ->actingAs(User::factory()->create())
            ->post('/api/guilds/' . $guild->id . '/channels' , [
                'name' => 'Test Channel',
                'description' => 'Test Description',
            ]);

        $request->assertStatus(201);
    }

    public function test_channels_require_a_name(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $request = $this
            ->actingAs(User::factory()->create())
            ->post('/api/guilds/' . $guild->id . '/channels', [
                'description' => 'Test Description',
            ]);

        $request->assertSessionHasErrors('name');
    }

    public function test_channels_require_a_valid_guild_id(): void
    {
        $user = User::factory()->create();

        Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $id = rand(1, 100);

        $request = $this
            ->actingAs(User::factory()->create())
            ->post("/api/guilds/{$id}/channels", [
                'name' => 'Test Channel',
                'description' => 'Test Description',
            ]);

        $request->assertStatus(404);
    }
}
