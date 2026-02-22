<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\UserAccessToken;
use App\Policies\SettingPolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
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

        // register policies
        Gate::policy(Setting::class, SettingPolicy::class);
    }
}
