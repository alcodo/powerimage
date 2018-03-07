<?php

namespace Alcodo\PowerImage\Listeners;

use Alcodo\PowerImage\Events\ResizedImageWasCreated;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;

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
