<?php

namespace Alcodo\PowerImage\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ImageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Page $page
     * @param User|null $user
     */
    public function __construct($fullUrl, $path, $absolutPath)
    {
    }
}
