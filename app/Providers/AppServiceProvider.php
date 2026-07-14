<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrap();
        Blade::if('permission', function (string $permission) {
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);
            $user->load('role.permissions');
        return $user->hasPermission($permission);
    }

    return false;
    });
    }
}
