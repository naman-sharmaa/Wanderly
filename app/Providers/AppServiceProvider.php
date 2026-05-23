<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\AllowPopupCOOP;

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
        // Use Bootstrap-style pagination (optional)
        Paginator::useBootstrapFive();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Ensure popups from Google/Firebase can be closed/inspected without COOP blocking.
        try {
            $router = $this->app->make('router');
            // Push middleware to the web group so it applies to web routes.
            if (method_exists($router, 'pushMiddlewareToGroup')) {
                $router->pushMiddlewareToGroup('web', AllowPopupCOOP::class);
            }
        } catch (\Throwable $e) {
            // If router is not available at this point, silently ignore.
        }
    }
}
