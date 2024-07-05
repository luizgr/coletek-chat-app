<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * @var RegisterService
     */
    protected $registerService;

    /**
     * RegisterController constructor.
     *
     * @param RegisterService $registerService
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    /**
     * Register a new user
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        return response()->json([
            'message'       => 'Success',
            'access_token'  => $this->registerService->register($request->validated()),
            'token_type'    => 'Bearer',
        ], Response::HTTP_CREATED);
    }
}
