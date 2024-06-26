<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class LoginFailedException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function render($request)
    {
        return response()->json([
            'message' => 'Login failed.',
            'errors' => [
                'email' => 'The provided credentials are incorrect.'
            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
