<?php

namespace Alcodo\PowerImage\Listeners;

use Alcodo\PowerImage\Events\ImageWasCreated;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImageListener
{
    /**
     * Handle the event.
     *
     * @param ImageWasCreated $event
     */
    public function handle(ImageWasCreated $event)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($event->absolutPath);
    }
}
