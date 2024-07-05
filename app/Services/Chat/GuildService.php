<?php

namespace App\Services\Chat;

use App\Contracts\Chat\GuildRepository;
use App\Exceptions\GuildNotFoundException;
use Illuminate\Support\Facades\Auth;

class GuildService
{
    /**
     * @var GuildRepository
     */
    private $guildRepository;

    /**
     * GuildService constructor.
     *
     * @param GuildRepository $guildRepository
     */
    public function __construct(GuildRepository $guildRepository)
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