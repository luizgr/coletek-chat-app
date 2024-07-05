<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Chat\ChannelController;
use App\Http\Controllers\Api\Chat\GuildController;
use App\Http\Controllers\Api\Chat\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/register', [RegisterController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/user', [AuthController::class, 'user']);
    
    Route::get('/guilds', [GuildController::class, 'index']);
    Route::get('/guilds/{id}', [GuildController::class, 'show']);
    Route::post('/guilds', [GuildController::class, 'store']);
    Route::delete('/guilds/{id}', [GuildController::class, 'destroy']);

    Route::get('/guilds/{guildId}/channels/{id}', [ChannelController::class, 'show']);
    Route::post('/guilds/{guildId}/channels', [ChannelController::class, 'store']);

    Route::post('/guilds/{guildId}/channels/{channelId}/messages', [MessageController::class, 'store']);
    Route::delete('/guilds/{guildId}/channels/{channelId}/messages/{id}', [MessageController::class, 'destroy']);
});
