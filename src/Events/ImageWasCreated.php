<?php

namespace Alcodo\PowerImage\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fullUrl;
    public $path;
    public $absolutPath;

    /**
     * Create a new event instance.
     *
     * @param $fullUrl
     * @param $path
     * @param $absolutPath
     */
    public function __construct($fullUrl, $path, $absolutPath)
    {
        $this->fullUrl = $fullUrl;
        $this->path = $path;
        $this->absolutPath = $absolutPath;
    }
}
