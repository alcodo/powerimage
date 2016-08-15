<?php

namespace Alcodo\PowerImage\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class PowerImageController extends Controller
{
    public function show($path, \League\Glide\Server $server)
    {
        $params = Input::query();

        // original image output
        if (empty($params)) {
            return $this->showOriginalImage($path, $server);
        }

        // resized which not exists must be optimized
        if ($server->cacheFileExists($path, $params) === false) {

            // generate image
            try {
                $cacheFile = $server->makeImage($path, $params);
            } catch (\Exception $e) {
                abort(404);
            }
            $absoluteFilepath = $this->getAbsoulteFilepath($cacheFile, $server);

            // optimize image
            $optimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
            $optimizer->optimizeImage($absoluteFilepath, pathinfo($path, PATHINFO_EXTENSION));
        }

        // resize image exists
        if (! isset($cacheFile)) {
            $cacheFile = $server->makeImage($path, $params);
        }

        $file = $server->getCache();

        $headers = [];
        $headers['Content-Type'] = $file->getMimetype($cacheFile);
        $headers['Content-Length'] = $file->getSize($cacheFile);
        $headers['Cache-Control'] = 'max-age=108000, public';
        $headers['Expires'] = date_create('+30 days')->format('D, d M Y H:i:s').' GMT';
        $headers['PowerImage'] = 'Compressed';

        return Response::make($file->read($cacheFile), 200, $headers);
    }

    protected function showOriginalImage($path, \League\Glide\Server $server)
    {
        $path = $server->getSourcePath($path);
        $file = $server->getCache();

        if ($file->has($path) === false) {
            abort(404);
        }

        $headers = [];
        $headers['Content-Type'] = $file->getMimetype($path);
        $headers['Content-Length'] = $file->getSize($path);
        $headers['Cache-Control'] = 'max-age=108000, public';
        $headers['Expires'] = date_create('+30 days')->format('D, d M Y H:i:s').' GMT';
        $headers['PowerImage'] = 'Original';

        return Response::make($file->read($path), 200, $headers);
    }

    protected function getAbsoulteFilepath($path, \League\Glide\Server $server)
    {
        /** @var Filesystem $filesystem */
        $filesystem = $server->getSource();

        /** @var Local $local */
        $local = $filesystem->getAdapter();

        return $local->getPathPrefix().$path;
    }
}
