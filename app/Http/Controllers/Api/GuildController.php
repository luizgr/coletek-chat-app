<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGuildRequest;
use App\Http\Resources\GuildResource;
use App\Services\GuildService;
use Illuminate\Http\Response;

class GuildController extends Controller
{
    /**
     *  Guild service
     *
     *  @var GuildService
     */
    protected $guildService;

    /**
     *  GuildController constructor.
     *
     *  @param GuildService $guildService
     */
    public function __construct(GuildService $guildService)
    {
        $this->guildService = $guildService;
    }

    /**
     *  Get all guilds
     * 
     *  @return \Illuminate\Http\Response
     * 
     *  @OA\GET(
     *      path="/api/guilds",
     *      summary="",
     *      description="Get all guilds",
     *      tags={"Guild"},
     *      security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function index()
    {
        return response()->json(
            GuildResource::collection($this->guildService->getAll()),
            Response::HTTP_OK
        );
    }

    /**
     *  Get guild by id with channels
     * 
     *  @param int $id
     *  @return \Illuminate\Http\Response
     * 
     *  @OA\GET(
     *      path="/api/guilds/{id}",
     *      summary="",
     *      description="Get guild by id with channels",
     *      tags={"Guild"},
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Guild id",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function show(int $id)
    {
        return response()->json(
            new GuildResource($this->guildService->getByIdWithChannels($id)),
            Response::HTTP_OK
        );
    }

    /**
     *  Create a new guild
     * 
     *  @param CreateGuildRequest $request
     *  @return \Illuminate\Http\Response
     * 
     *  @OA\POST(
     *      path="/api/guilds",
     *      summary="",
     *      description="Create a new guild",
     *      tags={"Guild"},
     *      security={{"sanctum": {}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Guild name",
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string",
     *                      description="Guild description",
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function store(CreateGuildRequest $request)
    {
        return response()->json(
            new GuildResource(
                $this->guildService->create(
                    $request->validated()
                )
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     *  Delete a guild
     * 
     *  @param int $id
     *  @return \Illuminate\Http\Response
     * 
     *  @OA\DELETE(
     *      path="/api/guilds/{id}",
     *      summary="",
     *      description="Delete a guild",
     *      tags={"Guild"},
     *      security={{"sanctum": {}}},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Guild id",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function destroy(int $id)
    {
        $this->guildService->delete($id);

        return response(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}