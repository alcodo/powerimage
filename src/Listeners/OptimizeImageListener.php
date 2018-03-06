<?php

namespace Alcodo\PowerImage\Listeners;

use Alcodo\PowerImage\Events\PowerImageWasCreated;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeImageListener
{
    /**
     * Handle the event.
     *
     * @param ImageWasCreated $event
     */
    public function handle(PowerImageWasCreated $event)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($event->convertedFilepathAbsoulte);
    }
}
