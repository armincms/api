<?php

namespace Armincms\Api;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova as LaravelNova;
use Armincms\Api\Http\Middleware\Authorize;
use Zareismail\Gutenberg\Gutenberg;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->routes();

        $this->app->booted(function() {
            \Config::set('sanctum.guard', 'api');
            \Config::set('auth.guards.api', config('auth.guards.web'));
        });
        
        Gutenberg::components([
            Cypress\Api::class,
        ]);

        LaravelNova::resources([
            Nova\Api::class,
        ]);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        } 

        Route::prefix('sanctum')->group(__DIR__.'/../routes/auth.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('verification.broker', function($app) {
            return new BrokerManager($app);
        });
    }
}
