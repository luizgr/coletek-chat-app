<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ChannelController;
use App\Http\Controllers\Api\GuildController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\TokenController;
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

Route::post('/token', [TokenController::class, 'create']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/user', [ProfileController::class, 'profile']);
    
    Route::get('/guilds', [GuildController::class, 'index']);
    Route::get('/guilds/{id}', [GuildController::class, 'show']);
    Route::post('/guilds', [GuildController::class, 'store']);
    Route::delete('/guilds/{id}', [GuildController::class, 'destroy']);

    Route::get('/guilds/{guildId}/channels/{id}', [ChannelController::class, 'show']);
    Route::post('/guilds/{guildId}/channels', [ChannelController::class, 'store']);

    Route::post('/guilds/{guildId}/channels/{channelId}/messages', [MessageController::class, 'store']);
    Route::delete('/guilds/{guildId}/channels/{channelId}/messages/{id}', [MessageController::class, 'destroy']);
});
