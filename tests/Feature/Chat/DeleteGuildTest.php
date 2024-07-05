<?php

namespace Tests\Feature\Chat;

use App\Models\Guild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteGuildTest extends TestCase
{
    use RefreshDatabase;

    public function test_guilds_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $guild = Guild::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $request = $this->delete('/api/guilds/' . $guild->id);

        $request->assertStatus(204);

        $this->assertNull($guild->fresh());
    }
}
