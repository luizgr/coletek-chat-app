<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Services\ChannelService;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    /**
     *  Channel service
     *
     *  @var ChannelService
     */
    protected $channelService;

    /**
     *  ChannelController constructor
     *
     *  @param ChannelService $channelService
     */
    public function __construct(ChannelService $channelService)
    {
        $this->channelService = $channelService;
    }

    /**
     *  Show a channel
     *
     *  @param int $guildId
     *  @param int $channelId
     *  @return \Illuminate\Http\JsonResponse
     * 
     *  @OA\Get(
     *      path="/api/guilds/{guildId}/channels/{channelId}",
     *      summary="",
     *      description="Show a channel",
     *      tags={"Channel"},
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
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *  )
     */
    public function show(int $guildId, int $channelId)
    {
        return response()->json(
            new ChannelResource(
                $this->channelService->getByIdWithLatestMessages($channelId)
            )
        );
    }

    /**
     *  Store a new channel
     *
     *  @param CreateChannelRequest $request
     *  @param int $guildId
     *  @return \Illuminate\Http\JsonResponse
     * 
     *  @OA\Post(
     *      path="/api/guilds/{guildId}/channels",
     *      summary="",
     *      description="Create a new channel",
     *      tags={"Channel"},
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="guildId",
     *          in="path",
     *          description="Guild id",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="general")
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
    public function store(CreateChannelRequest $request, int $guildId)
    {
        return response()->json(
            $this->channelService->create(array_merge(
                $request->validated(),
                ['guild_id' => $guildId]
            )),
            Response::HTTP_CREATED
        );
    }
}