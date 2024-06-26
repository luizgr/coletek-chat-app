<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * Get message by id
     *
     * @param int $id
     * @return Message
     */
    public function getById(int $id): Message
    {
        return Message::findOrFail($id);
    }

    /**
     * Create a new message
     *
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * Delete message by id
     *
     * @param int $id
     * @return Message
     */
    public function delete(int $id): void
    {
        Message::destroy($id);
    }
}