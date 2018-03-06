<?php

namespace Alcodo\PowerImage\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PowerImageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;

    public $originalFilepath;
    public $originalFilepathAbsoulte;

    public $convertedFilepath;
    public $convertedFilepathAbsoulte;

    public $absolutPath;

    /**
     * Create a new event instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param $path
     * @param $absolutPath
     */
    public function __construct($request, $originalFilepath, $convertedFilePath)
    {
        $this->request = $request;

        // orignial
        $this->originalFilepath = $originalFilepath;
        $this->originalFilepathAbsoulte = Storage::path($originalFilepath);

        // converted
        $this->convertedFilepath = $convertedFilePath;
        $this->convertedFilepathAbsoulte = Storage::path($convertedFilePath);
    }
}
