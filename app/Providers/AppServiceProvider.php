<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Event::listen(Login::class, function (Login $event) {
            activity('auth')
                ->causedBy($event->user)
                ->log('User logged in');
        });

        \Illuminate\Support\Facades\Gate::define('validate-transactions', function (\App\Models\User $user) {
            return $user->role === 'kelian';
        });

        \Illuminate\Support\Facades\Gate::define('manage-users', function (\App\Models\User $user) {
            return $user->role === 'kelian';
        });

        \Illuminate\Support\Facades\Gate::define('manage-funds', function (\App\Models\User $user) {
            return $user->role === 'treasurer';
        });

        \Illuminate\Support\Facades\Gate::define('view-reports', function (\App\Models\User $user) {
            return in_array($user->role, ['kelian', 'treasurer']);
        });
    }
}
