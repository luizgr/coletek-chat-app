<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTokenRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
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
     * Get the authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\POST(
     *     path="/api/user",
     *     summary="",
     *     description="User profile",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     * )
     */
    function user()
    {
        return response()->json(
            new UserResource(
                $this->authService->user()
            ), Response::HTTP_OK
        );
    }

    /**
     * Login user with email and password
     * 
     * @param CreateTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\POST(
     *     path="/api/auth/login",
     *     summary="",
     *     description="Login user with email and password",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="Email",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="Password",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        return response()->json([
            'message'       => 'Success',
            'access_token'  => $this->authService->login($request->validated()),
            'token_type'    => 'Bearer'
        ], Response::HTTP_OK);
    }

    /**
     * Logout user
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @OA\POST(
     *     path="/api/auth/logout",
     *     summary="",
     *     description="Logout user",
     *     tags={"Auth"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     * )
     */
    public function logout()
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Success'
        ], Response::HTTP_OK);
    }
}
