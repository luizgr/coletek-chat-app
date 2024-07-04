<?php

namespace App\Services;

use App\Exceptions\GuildNotFoundException;
use App\Models\Channel;
use App\Repositories\ChannelRepositoryInterface;
use App\Repositories\GuildRepositoryInterface;

class ChannelService
{
    /**
     * Channel repository
     *
     * @var ChannelRepositoryInterface
     */
    protected $channelRepository;

    /**
     * Guild repository
     *
     * @var GuildRepositoryInterface
     */
    protected $guildRepository;

    /**
     * ChannelService constructor
     *
     * @param ChannelRepositoryInterface $channelRepository
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository, 
        GuildRepositoryInterface $guildRepository)
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