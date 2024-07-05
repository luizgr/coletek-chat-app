<?php

namespace App\Services\Chat;

use App\Contracts\Chat\MessageRepository;
use App\Events\DeletedMessage;
use App\Events\GotMessage;

class MessageService
{
    /**
     * @var MessageRepository
     */
    private $messageRepository;

    /**
     * MessageService constructor.
     *
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Send a message to a channel
     *
     * @param string $message
     * @param int $channelId
     * @return Message|null
     */
    public function sendMessage(array $data): void
    {
        $message = $this->messageRepository->create([
            'content'       => $data['content'],
            'channel_id'    => $data['channel_id'],
            'user_id'       => auth()->id(),
        ]);

        GotMessage::dispatch($message);
    }

    /**
     * Delete a message
     *
     * @param int $id
     * @return bool
     */
    public function deleteMessage(int $id)
    {
        $message = $this->messageRepository->getById($id);

        DeletedMessage::dispatch($message->id, $message->channel_id);
        
        $this->messageRepository->delete($id);
    }
}