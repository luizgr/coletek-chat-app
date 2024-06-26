<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class GuildNotFoundException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'message' => 'Guild not found'
        ], Response::HTTP_NOT_FOUND);
    }
}
