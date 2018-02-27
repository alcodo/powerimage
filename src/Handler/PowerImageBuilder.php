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
        $parameterString = $this->getParameterString($request->path(), $ext);
        if (!$parameterString) {
            Log::debug('powerimage: no parameter found in string: ' . $request->path());
            return false;
        }

        $originalFilepath = $this->getOriginalFilepath($request->path(), $parameterString);
        if (!Storage::exists($originalFilepath)) {
            Log::debug('powerimage: original image file not exits, path: ' . $originalFilepath);
            return false;
        }

        // Get convert params

        // Convert image

        // Output the image

        // TODO
    }

    /**
     * from:
     * images/car_w:200,h:200.jpg
     *
     * to:
     * w:200,h:200
     *
     * @param $path
     * @param $fileextension
     * @return bool
     */
    public function getParameterString($path, $fileextension)
    {
        preg_match('/_(.*?).' . $fileextension . '/', $path, $match);

        if (!isset($match[1]) || empty($match[1])) {
            return false;
        }

        return $match[1];
    }

    /**
     * It converts path
     *
     * from:
     * images/car_w:200,h:200.jpg
     *
     * to:
     * images/car.jpg
     *
     * @param $path
     * @return mixed
     */
    public function getOriginalFilepath($path, $parameter)
    {
        return str_replace($this->delimiter . $parameter, '', $path);
    }

}