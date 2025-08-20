<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\PlatformManagerPolicy;
use App\Policies\RolePolicy;
use App\Policies\SiteUserPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

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
        Gate::policy(Role::class, RolePolicy::class);
        // Gate::policy(User::class, PlatformManagerPolicy::class);
        // Gate::policy(User::class, SiteUserPolicy::class);
        // Gate::policy(User::class, ClientPolicy::class);

        Gate::policy(User::class, UserPolicy::class);
    }
}
