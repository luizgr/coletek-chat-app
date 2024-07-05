<?php

namespace App\Repositories\Chat;

use App\Contracts\Chat\GuildRepository;
use App\Models\Guild;

class GuildDatabaseRepository implements GuildRepository
{
    /**
     * Get all guilds
     *
     * @return array
     */
    public function getAll(): array
    {
        return iterator_to_array(Guild::all());
    }

    /**
     * Get guild by id
     *
     * @param int $id
     * @return Guild|null
     */
    public function getById(int $id): ?Guild
    {
        return Guild::find($id);
    }
    
    /**
     * Get guild by id with channels
     *
     * @param int $id
     * @return Guild|null
     */
    public function getByIdWithChannels(int $id): ?Guild
    {
        return Guild::with('channels')->find($id);
    }

    /**
     * Create a new guild
     *
     * @param array $data
     * @return Guild
     */
    public function create(array $data): Guild
    {
        return Guild::create($data);
    }

    /**
     * Update a guild
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function delete(int $id): bool
    {
        $guild = Guild::find($id);
        $guild->channels()->delete();
        return $guild->delete();
    }
}