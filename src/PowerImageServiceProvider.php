<?php

namespace Alcodo\PowerImage;

use Alcodo\PowerImage\Events\ImageWasCreated;
use Alcodo\PowerImage\Handler\PowerImageBuilder;
use Alcodo\PowerImage\Listeners\OptimizeImageListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider as Provider;
use League\Glide\ServerFactory;

class PowerImageServiceProvider extends Provider
{
    /**
     * @var array
     */
    protected $listen = [
        ImageWasCreated::class => [
            OptimizeImageListener::class,
        ],
    ];


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

        // register events
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
