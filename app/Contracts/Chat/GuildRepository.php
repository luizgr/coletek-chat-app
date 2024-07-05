<?php

namespace App\Contracts\Chat;

use App\Models\Guild;

interface GuildRepository
{
    /**
     * Get all guilds
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Get guild by id
     *
     * @param int $id
     * @return Guild|null
     */
    public function getById(int $id): ?Guild;

    /**
     * Get guild by id with channels
     *
     * @param int $id
     * @return Guild|null
     */
    public function getByIdWithChannels(int $id): ?Guild;

    /**
     * Create a new guild
     *
     * @param array $data
     * @return Guild
     */
    public function create(array $data): Guild;

    /**
     * Delete a guild
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}