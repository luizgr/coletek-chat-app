<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMessageRequest;
use App\Services\MessageService;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    /**
     *  Message service
     *
     *  @var MessageService
     */
    protected $messageService;

    /**
     *  MessageController constructor
     *
     * @param ChannelService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     *  Store a new message
     *
     *  @param CreateMessageRequest $request
     *  @param int $guildId
     *  @param int $channelId
     *  @return \Illuminate\Http\JsonResponse
     * 
     *  @OA\Post(
     *      path="/api/guilds/{guildId}/channels/{channelId}/messages",
     *      summary="",
     *      description="Send a message to a channel",
     *      tags={"Message"},
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="guildId",
     *          in="path",
     *          description="Guild id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="channelId",
     *          in="path",
     *          description="Channel id",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"content"},
     *              @OA\Property(property="content", type="string", example="Hello world")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *  )
     */
    public function store(CreateMessageRequest $request, int $guildId, int $channelId)
    {
        $this->messageService->sendMessage([
            'content'       => $request->input('content'),
            'channel_id'    => $channelId
        ]);

        return response()->json(
            ['message' => 'Message sent'],
            Response::HTTP_CREATED
        );
    }

    /**
     *  Delete a message
     *
     *  @param int $guildId
     *  @param int $channelId
     *  @param int $messageId
     *  @return \Illuminate\Http\JsonResponse
     * 
     *  @OA\Delete(
     *      path="/api/guilds/{guildId}/channels/{channelId}/messages/{messageId}",
     *      summary="",
     *      description="Delete a message",
     *      tags={"Message"},
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="guildId",
     *          in="path",
     *          description="Guild id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="channelId",
     *          in="path",
     *          description="Channel id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="messageId",
     *          in="path",
     *          description="Message id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *  )
     */
    public function destroy(int $guildId, int $channelId, int $messageId)
    {
        $this->messageService->deleteMessage($messageId);

        return response()->json(
            ['message' => 'Message deleted'],
            Response::HTTP_OK
        );
    }
}