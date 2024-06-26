<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    /**
    *  @OA\POST(
    *      path="/api/user",
    *      summary="",
    *      description="User profile",
    *      tags={"Auth"},
    *      security={{"sanctum":{}}},
    *      @OA\Response(
    *          response=200,
    *          description="OK",
    *          @OA\MediaType(
    *              mediaType="application/json",
    *          )
    *      ),
    *  )
    */
    function profile(Request $request)
    {
        return response()->json(
            new UserResource(
                $request->user()
            ), Response::HTTP_OK
        );
    }
}
