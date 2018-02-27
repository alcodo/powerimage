<?php

namespace Alcodo\PowerImage\Handler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class PowerImageBuilder
{
    protected $delimiter = '_';

    protected $imageExtensions = [
        'gif',
        'jpg',
        'jpeg',
        'png',
    ];

    /**
     * Check if powerimage can handle this image
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function check($request, \Exception $exception)
    {
        // check exception
        if (!$exception instanceof NotFoundHttpException) {
            return false;
        }

        // check method
        if ($request->getMethod() != 'GET') {
            return false;
        }

        // check path has delimiter
        if (strpos($request->path(), $this->delimiter) === false) {
            Log::debug('powerimage: delimiter not found: ' . $this->delimiter);
            return false;
        }

        // check path has image extension
        $ext = pathinfo($request->path(), PATHINFO_EXTENSION);
        if (!in_array($ext, $this->imageExtensions)) {
            Log::debug('powerimage: image extension not found: ' . $ext);
            return false;
        }

        // check original image file
        $originalFilepath = $this->getOriginalFilepath($request->path());
        if(! Storage::exists($originalFilepath)){
            Log::debug('powerimage: original image file not exits, path: ' . $originalFilepath);
            return false;
        }

        // Get convert params

        // Convert image

        // Output the image

        // TODO
    }

}