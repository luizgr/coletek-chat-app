<?php

namespace App\Repositories;

use App\Models\Channel;

interface ChannelRepositoryInterface
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