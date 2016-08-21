<?php

namespace Alcodo\PowerImage;

use Illuminate\Support\ServiceProvider as Provider;

class PowerImageServiceProvider extends Provider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->register('Approached\LaravelImageOptimizer\ServiceProvider');
    }

    public function boot()
    {
        //        $this->app->singleton('League\Glide\Server', function () {
//            $filesystemDriver = app('filesystem')->getDriver();
//
//            return \League\Glide\ServerFactory::create([
//                'source' => $filesystemDriver,
//                'cache' => $filesystemDriver,
//                'source_path_prefix' => 'powerimage',
//                'cache_path_prefix' => 'powerimage/.cache',
//                'base_url' => 'img',
//            ]);
//        });

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }
    }
}
