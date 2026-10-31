<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\User;
use App\Observers\UserObserver;
use App\Policies\AppointmentPolicy;
use Illuminate\Support\Facades\Gate;
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
        User::observe(UserObserver::class);
        Gate::policy(Appointment::class, AppointmentPolicy::class);
    }
}
