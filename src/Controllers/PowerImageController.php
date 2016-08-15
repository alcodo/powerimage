<?php

namespace Alcodo\PowerImage\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class PowerImageController extends Controller
{
    public function show($filepath, \League\Glide\Server $server)
    {
        $params = Input::query();

        // original image output
        if (empty($params)) {
            return $this->showOriginalImage($filepath, $server);
        }

        if ($server->cacheFileExists($filepath, $params) === false) {
            // resize file must created
            $cacheFile = $this->generateImage($filepath, $params, $server);
        } else {
            // resize file exists
            $cacheFile = $server->makeImage($filepath, $params);
        }

        // response
        $file = $server->getCache();
        $headers = [];
        $headers['Content-Type'] = $file->getMimetype($cacheFile);
        $headers['Content-Length'] = $file->getSize($cacheFile);
        $headers['Cache-Control'] = 'max-age=2592000, public';
        $headers['Expires'] = date_create('+30 days')->format('D, d M Y H:i:s').' GMT';
        $headers['PowerImage'] = 'Compressed';

        return Response::make($file->read($cacheFile), 200, $headers);
    }

    protected function showOriginalImage($filepath, \League\Glide\Server $server)
    {
        $filepath = $server->getSourcePath($filepath);
        $file = $server->getCache();

        if ($file->has($filepath) === false) {
            abort(404);
        }

        // response
        $headers = [];
        $headers['Content-Type'] = $file->getMimetype($filepath);
        $headers['Content-Length'] = $file->getSize($filepath);
        $headers['Cache-Control'] = 'max-age=7776000, public';
        $headers['Expires'] = date_create('+90 days')->format('D, d M Y H:i:s').' GMT';
        $headers['PowerImage'] = 'Original';

        return Response::make($file->read($filepath), 200, $headers);
    }

    /**
     * Returns a absoulte filepath from path.
     *
     * @param $filepath
     * @param \League\Glide\Server $server
     * @return string
     */
    protected function getAbsoulteFilepath($filepath, \League\Glide\Server $server)
    {
        /** @var Filesystem $filesystem */
        $filesystem = $server->getSource();

        /** @var Local $local */
        $local = $filesystem->getAdapter();

        return $local->getPathPrefix().$filepath;
    }

    /**
     * Image will be resize and optimized.
     *
     * @param $filepath
     * @param $params
     * @param $server
     * @return string
     */
    private function generateImage($filepath, $params, $server)
    {
        // resize image
        try {
            $cacheFile = $server->makeImage($filepath, $params);
        } catch (\Exception $e) {
            abort(404);
        }
        $absoluteFilepath = $this->getAbsoulteFilepath($cacheFile, $server);

        // optimize image
        $optimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
        $optimizer->optimizeImage($absoluteFilepath, pathinfo($filepath, PATHINFO_EXTENSION));

        return $cacheFile;
    }
}
