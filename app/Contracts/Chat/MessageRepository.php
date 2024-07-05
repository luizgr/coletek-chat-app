<?php

namespace App\Contracts\Chat;

use App\Models\Message;

interface MessageRepository
{
    /**
     * Get message by id
     *
     * @param int $id
     * @return Message
     */
    public function getById(int $id): Message;

    /**
     * Create a new message
     *
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message;

    /**
     * Delete message by id
     *
     * @param int $id
     * @return Message
     */
    public function delete(int $id): void;
}