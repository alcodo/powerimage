<?php namespace Alcodo\PowerImage;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        if (!$this->app->routesAreCached()) {
                require __DIR__ . '/routes.php';
        }
    }
}