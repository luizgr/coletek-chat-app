<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTokenRequest;
use App\Services\AuthService;
use Illuminate\Http\Response;

class TokenController extends Controller
{
    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * LoginController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
    *  @OA\POST(
    *      path="/api/token",
    *      summary="",
    *      description="Login user with email and password",
    *      tags={"Auth"},
    *      @OA\RequestBody(
    *          required=true,
    *          @OA\MediaType(
    *              mediaType="application/json",
    *              @OA\Schema(
    *                  @OA\Property(
    *                      property="email",
    *                      type="string",
    *                      description="Email",
    *                  ),
    *                  @OA\Property(
    *                      property="password",
    *                      type="string",
    *                      description="Password",
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
    function create(CreateTokenRequest $request)
    {
        return response()->json([
            'message'       => 'Success',
            'access_token'  => $this->authService->login($request->validated()),
            'token_type'    => 'Bearer'
        ], Response::HTTP_OK);
    }
}
