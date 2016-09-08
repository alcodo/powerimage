<?php

namespace Alcodo\PowerImage\Jobs;

use Alcodo\PowerImage\Utilities\UrlHelper;
use Approached\LaravelImageOptimizer\ImageOptimizer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Storage;
use League\Glide\Api\Api;

class ResizeImage implements SelfHandling
{

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
    public function handle(ImageOptimizer $imageOptimizer)
    {
        // get
        $originalFileBinary = Storage::disk('powerimage')->get($this->orignalFile);

        // convert
        /** @var Api $glideApi */
        $glideApi = app('GlideApi');
        $resizeFileBinary = $glideApi->run($originalFileBinary, $this->params);

        // save
        $resizedFilepath = $this->getResizeFilepath();
        Storage::disk('powerimage')->put($resizedFilepath, $resizeFileBinary);

        // optimize (overwrite image file)
        $absoluteResizeFilepath = storage_path('powerimage'.$resizedFilepath);
        $imageOptimizer->optimizeImage($absoluteResizeFilepath);

        return UrlHelper::getPowerImageUrlPath($resizedFilepath);
    }

    /**
     * @return string
     */
    protected function getResizeFilepath()
    {
        $file = pathinfo($this->orignalFile);

        $directory = $file['dirname'];
        if ($directory == '.') {
            $directory = '';
        }
        $directory = str_replace('/powerimage', '', $directory);

        return $directory.'/'.$this->getParamsAsString().'/'.$file['basename'];
    }

    /**
     * Converts params to follow syntax:
     * w_250,h_250.
     *
     * @return string
     */
    protected function getParamsAsString()
    {
        $params = http_build_query($this->params, null, ',');

        return str_replace('=', '_', $params);
    }
}
