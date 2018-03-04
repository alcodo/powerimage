<?php

namespace Alcodo\PowerImage;

use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Illuminate\Support\ServiceProvider as Provider;
use League\Glide\ServerFactory;

class PowerImageServiceProvider extends Provider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        $this->app->singleton('GlideApi', function () {
            $factory = new ServerFactory([]);

            return $factory->getApi();
        });

        $this->app->singleton('powerimage', function ($app) {
            return new PowerImageBuilder();
        });
    }
}
