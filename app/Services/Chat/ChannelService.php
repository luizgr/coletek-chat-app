<?php

namespace App\Services\Chat;

use App\Contracts\Chat\ChannelRepository;
use App\Contracts\Chat\GuildRepository;
use App\Exceptions\GuildNotFoundException;
use App\Models\Channel;

class ChannelService
{
    /**
     * Channel repository
     *
     * @var ChannelRepository
     */
    protected $channelRepository;

    /**
     * Guild repository
     *
     * @var GuildRepository
     */
    protected $guildRepository;

    /**
     * ChannelService constructor
     *
     * @param ChannelRepository $channelRepository
     */
    public function __construct(
        ChannelRepository $channelRepository, 
        GuildRepository $guildRepository)
    {
        $this->channelRepository = $channelRepository;
        $this->guildRepository = $guildRepository;
    }

    /**
     * Get channel by id with latest messages
     *
     * @param int $id
     * @return Channel|null
     */
    public function getByIdWithLatestMessages(int $id): ?Channel
    {
        return $this->channelRepository->getByIdWithLatestMessages($id);
    }

    /**
     * Create a new channel
     *
     * @param array $data
     * @return Channel
     */
    public function create(array $data): Channel
    {
        if (!$this->guildRepository->getById($data['guild_id'])) {
            throw new GuildNotFoundException();
        }

        return $this->channelRepository->create([
            'name'          => $data['name'],
            'description'   => $data['description'] ?? null,
            'guild_id'      => $data['guild_id'],
        ]);
    }
}