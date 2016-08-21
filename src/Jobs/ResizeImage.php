<?php

namespace Alcodo\PowerImage\Jobs;

use Approached\LaravelImageOptimizer\ImageOptimizer;
use Cocur\Slugify\Slugify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    protected function getParamsAsString()
    {
        $paramsWithKeyAndValues = [];
        foreach ($this->params as $key => $value) {
            $paramsWithKeyAndValues[] = $key . '_' . $value;
        }

        return implode(',', $paramsWithKeyAndValues);
    }
}
