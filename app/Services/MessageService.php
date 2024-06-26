<?php

namespace App\Services;

use App\Events\DeletedMessage;
use App\Events\GotMessage;
use App\Jobs\SendMessage;
use App\Repositories\MessageRepositoryInterface;

class MessageService
{
    /**
     * @var MessageRepositoryInterface
     */
    private $messageRepository;

    /**
     * MessageService constructor.
     *
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(MessageRepositoryInterface $messageRepository)
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