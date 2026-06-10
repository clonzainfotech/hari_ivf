<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // The app's theme is Bootstrap 4; render pagination with Bootstrap markup
        // instead of Laravel's default Tailwind view (whose SVG chevrons render
        // huge without Tailwind CSS).
        Paginator::useBootstrap();
    }
}
