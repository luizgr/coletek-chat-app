<?php

namespace App\Repositories;

use App\Models\Channel;

class ChannelRepository implements ChannelRepositoryInterface
{
    /**
     * Get channel by id
     *
     * @param int $id
     * @return Channel|null
     */
    public function getById(int $id): ?Channel
    {
        return Channel::find($id);
    }

    /**
     * Create a new channel
     *
     * @param array $data
     * @return Channel
     */
    public function create(array $data): Channel
    {
        return Channel::create($data);
    }
}