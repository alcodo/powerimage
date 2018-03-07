<?php

namespace Alcodo\PowerImage\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResizedImageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * @var string
     */
    public $originalFilepath;

    /**
     * @var string
     */
    public $convertedFilepath;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param $originalFilepath
     * @param $convertedFilePath
     */
    public function __construct($request, $originalFilepath, $convertedFilePath)
    {
        $this->request = $request;
        $this->originalFilepath = $originalFilepath;
        $this->convertedFilepath = $convertedFilePath;
    }
}
