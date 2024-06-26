<?php

namespace App\Services;

use App\Exceptions\GuildNotFoundException;
use App\Repositories\GuildRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GuildService
{
    /**
     * @var GuildRepositoryInterface
     */
    private $guildRepository;

    /**
     * GuildService constructor.
     *
     * @param GuildRepositoryInterface $guildRepository
     */
    public function __construct(GuildRepositoryInterface $guildRepository)
    {
        $this->guildRepository = $guildRepository;
    }

    /**
     * Get all guilds
     *
     * @return array
     */
    public function getAll()
    {
        return $this->guildRepository->getAll();
    }

    /**
     * Get guild by id with channels
     *
     * @param int $id
     * @return Guild|null
     */
    public function getByIdWithChannels(int $id)
    {
        if (!$this->guildRepository->getById($id)) {
            throw new GuildNotFoundException();
        }

        return $this->guildRepository->getByIdWithChannels($id);
    }

    /**
     * Create a new guild
     *
     * @param array $data
     * @return Guild
     */
    public function create(array $data)
    {
        return $this->guildRepository->create([
            'name'          => $data['name'],
            'description'   => $data['description'] ?? null,
            'user_id'       => Auth::user()->id,
        ]);
    }

    /**
     * Delete a guild
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->guildRepository->delete($id);
    }
}