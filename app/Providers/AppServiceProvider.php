<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Auth::extend('jwt', function ($app, $name, array $config) {
            $userProvider = Auth::createUserProvider($config['provider'] ?? null);
            $request = $app['request'];

            return new JwtGuard($userProvider, $request);
        });
    }
}
