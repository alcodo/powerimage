<?php

namespace Alcodo\PowerImage\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ResizeImage implements SelfHandling
{
    use Queueable;

    /**
     * @var string
     */
    private $orignalFile;
    /**
     * @var array
     */
    private $params;

    /**
     * ResizeImage constructor.
     * @param string $orignalFile
     * @param array $params
     */
    public function __construct($orignalFile, $params)
    {
        $this->orignalFile = $orignalFile;
        $this->params = $params;

        if (Storage::disk('powerimage')->exists($this->orignalFile) === false) {
            abort(404, 'Original file does not exists');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $resizedFilepath = $this->getResizeFilepath();

        // copy
        Storage::disk('powerimage')->copy($this->orignalFile, $resizedFilepath);

        // resize
        // TODO

        return $resizedFilepath;
    }

    protected function getResizeFilepath()
    {
        $file = pathinfo($this->orignalFile);
        $directory = $file['dirname'];

        return $directory . '/' . $this->getParamsAsString() . '/' . $file['basename'];
    }

    /**
     * Converts params to follow syntax:
     * w_250,h_250
     *
     * @return string
     */
    protected function getParamsAsString()
    {
        $params = http_build_query($this->params, null, ',');
        return str_replace('=', '_', $params);
    }
}
