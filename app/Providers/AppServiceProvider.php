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

        // Legacy resilience: this codebase predates PHP 8 and accesses decoded JSON
        // (stdClass) properties / array keys without guards throughout its Blade views,
        // relying on the old behaviour where a missing property/key silently yields null.
        // On PHP 8 those became E_WARNINGs that Laravel escalates to fatal ErrorExceptions,
        // so incomplete/legacy records 500 the page. Restore the original intent by making
        // ONLY these specific access warnings non-fatal (evaluate to null); every other
        // error is delegated to Laravel's handler unchanged, so real bugs still surface.
        $previousHandler = set_error_handler(function ($level, $message, $file = '', $line = 0) use (&$previousHandler) {
            $legacyAccessWarning = in_array($level, [E_WARNING, E_NOTICE, E_DEPRECATED], true) && (
                str_contains($message, 'Undefined property') ||
                str_contains($message, 'Undefined array key') ||
                str_contains($message, 'Undefined variable') ||
                str_contains($message, 'Attempt to read property') ||
                str_contains($message, 'Trying to access array offset')
            );

            if ($legacyAccessWarning) {
                return true; // swallow: the offending expression evaluates to null
            }

            // Not a legacy access warning — hand back to Laravel's handler (which throws).
            return $previousHandler ? ($previousHandler)($level, $message, $file, $line) : false;
        });
    }
}
