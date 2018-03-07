<?php

namespace Alcodo\PowerImage\Handler;

use Alcodo\PowerImage\Events\ResizedImageWasCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Check if powerimage can handle this image.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     *
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

        // check path has a image extension
        $ext = pathinfo($request->path(), PATHINFO_EXTENSION);
        if (!in_array($ext, $this->imageExtensions)) {
            Log::debug('powerimage: image extension not found: ' . $ext);

            return false;
        }

        // check parameter to parse
        $parameterString = ParamsHelper::getParameterString($request->path(), $ext);
        if (!$parameterString) {
            Log::debug('powerimage: no parameter found in string: ' . $request->path());

            return false;
        }

        // check original image file exits
        $originalFilepath = $this->getOriginalFilepath($request->path(), $parameterString);
        if (!Storage::exists($originalFilepath)) {
            Log::debug('powerimage: original image file not exits, path: ' . $originalFilepath);
            Log::debug('powerimage: storage package check follow absolut file: ' . Storage::path($originalFilepath));

            return false;
        }

        // convert params
        $params = ParamsHelper::parseToArray($parameterString);

        // Convert
        /** @var Api $glideApi */
        $glideApi = app('GlideApi');
        $resizedFileBinary = $glideApi->run(Storage::path($originalFilepath), $params);

        // Save
        Storage::put($request->path(), $resizedFileBinary);
        if (!Storage::exists($request->path())) {
            Log::debug('powerimage: image was not saved, binarycode length: ' . strlen($resizedFileBinary));

            return false;
        }

        // Output the image
        event(
            new ResizedImageWasCreated($request, $originalFilepath, $request->path())
        );
        header('Location:' . $request->url(), true, 301);
        exit;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $paths
     */
    public function include($request, array $paths)
    {
        foreach ($paths as $path) {
            if (ExceptionCheck::check($request->getPathInfo(), $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param array $paths
     */
    public function exclude($request, array $paths)
    {
        return !$this->include($request, $paths);
    }

    public function path(string $path, array $params): string
    {
        $filename = basename($path);

        $newFilename = pathinfo($path, PATHINFO_FILENAME) .
            $this->delimiter .
            ParamsHelper::parseToString($params) .
            '.' .
            pathinfo($path, PATHINFO_EXTENSION);

        return str_replace($filename, $newFilename, $path);
    }

    /**
     * It converts path.
     *
     * from:
     * images/car_w:200,h:200.jpg
     *
     * to:
     * images/car.jpg
     *
     * @param $path
     *
     * @return mixed
     */
    public function getOriginalFilepath($path, $parameter)
    {
        return str_replace($this->delimiter . $parameter, '', $path);
    }
}
