<?php

namespace Tests\Feature\Chat;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuildListTest extends TestCase
{
    use RefreshDatabase;

    public function test_guilds_can_be_listed(): void
    {
        $request = $this
            ->actingAs(User::factory()->create())
            ->get('/api/guilds');

        $request->assertStatus(200);
    }
}
