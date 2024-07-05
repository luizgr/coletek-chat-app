<?php

namespace App\Providers;

use App\Contracts\Chat\ChannelRepository;
use App\Contracts\Chat\GuildRepository;
use App\Contracts\Chat\MessageRepository;
use App\Contracts\User\UserRepository;
use App\Repositories\Chat\ChannelDatabaseRepository;
use App\Repositories\Chat\GuildDatabaseRepository;
use App\Repositories\Chat\MessageDatabaseRepository;
use App\Repositories\User\UserDatabaseRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepository::class, UserDatabaseRepository::class);
        $this->app->bind(GuildRepository::class, GuildDatabaseRepository::class);
        $this->app->bind(ChannelRepository::class, ChannelDatabaseRepository::class);
        $this->app->bind(MessageRepository::class, MessageDatabaseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
