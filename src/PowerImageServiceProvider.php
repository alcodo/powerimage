<?php

namespace Alcodo\PowerImage;

use Illuminate\Support\ServiceProvider as Provider;
use League\Glide\ServerFactory;
use Alcodo\PowerImage\Handler\PowerImageBuilder;

class PowerImageServiceProvider extends Provider
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
        $this->app->singleton('GlideApi', function () {
            $factory = new ServerFactory([]);

            return $factory->getApi();
        });

        $this->app->singleton('powerimage', function ($app) {
            return new PowerImageBuilder();
        });
    }
}
