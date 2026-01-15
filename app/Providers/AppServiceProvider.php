<?php

namespace App\Providers;

use App\Models\UserAccessToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
        Model::preventLazyLoading(!app()->isProduction());

        Sanctum::usePersonalAccessTokenModel(UserAccessToken::class);
    }
}
