<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class PassportGrantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            RefreshTokenRepositoryInterface::class,
            RefreshTokenRepository::class,
        );

        $this->app->afterResolving(AuthorizationServer::class, function (AuthorizationServer $server): void {
            foreach (config('passportgrant.grants', []) as $grantClass) {
                $grant = $this->app->make($grantClass);
                $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
                $server->enableGrantType($grant, Passport::tokensExpireIn());
            }
        });
    }
}