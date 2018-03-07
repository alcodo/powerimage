<?php

namespace Alcodo\PowerImage\Listeners;

use Alcodo\PowerImage\Events\ResizedImageWasCreated;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImageListener
{
    /**
     * Handle the event.
     *
     * @param ResizedImageWasCreated $event
     */
    public function handle(ResizedImageWasCreated $event)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize(
            Storage::path($event->convertedFilepath)
        );
    }
}
