<?php

namespace Alcodo\PowerImage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alcodo\PowerImage\Handler\PowerImageBuilder
 */
class PowerImage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'powerimage';
    }
}
