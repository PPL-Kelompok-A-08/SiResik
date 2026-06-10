<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SampahLiar;
use App\Policies\SampahLiarPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(SampahLiar::class, SampahLiarPolicy::class);
    }
}
