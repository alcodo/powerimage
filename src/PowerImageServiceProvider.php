<?php

namespace Alcodo\PowerImage;

use Illuminate\Support\ServiceProvider as Provider;
use League\Glide\ServerFactory;

class PowerImageServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
//        $this->app->register('Approached\LaravelImageOptimizer\ServiceProvider');
    }

    public function boot()
    {
        $this->app->singleton('GlideApi', function () {
            $factory = new ServerFactory([]);

            return $factory->getApi();
        });
    }
}
