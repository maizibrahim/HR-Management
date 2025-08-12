<?php

namespace App\Providers;

use App\Models\leave\LeaveRequest;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        LeaveRequest::class => LeaveRequestPolicy::class,
    ];

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
        //
    }
}
