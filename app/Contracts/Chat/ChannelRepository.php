<?php

namespace App\Contracts\Chat;

use App\Models\Channel;

interface ChannelRepository
{
    /**
     * Get channel by id
     *
     * @param int $id
     * @return Channel|null
     */
    public function getByIdWithLatestMessages(int $id): ?Channel;

    /**
     * Create a new channel
     *
     * @param array $data
     * @return Channel
     */
    public function create(array $data): Channel;
}