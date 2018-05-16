<?php

namespace App\Providers;

use App\Services\JWTService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JWTService::class, function ($app) {
            return new JWTService();
        });
    }
}
