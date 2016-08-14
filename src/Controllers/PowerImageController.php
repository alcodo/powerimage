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

        return $server->outputImage($path, Input::query());
    }

    protected function showOriginalImage($path, \League\Glide\Server $server)
    {
        $path = $server->getSourcePath($path);

        $filesystem = app('filesystem');

        if ($filesystem->exists($path) === false) {
            abort(404);
        }

        $content = $filesystem->get($path);

        $headers = [];
        $headers['Content-Type'] = $filesystem->mimeType($path);
        $headers['Content-Length'] = $filesystem->getSize($path);
        $headers['Cache-Control'] = 'max-age=31536000, public';
        $headers['Expires'] = date_create('+1 years')->format('D, d M Y H:i:s') . ' GMT';

        return Response::make($content, 200, $headers);
    }

    protected function getAbsoulteFilepath($path, \League\Glide\Server $server)
    {
        /** @var Filesystem $filesystem */
        $filesystem = $server->getSource();

        /** @var Local $local */
        $local = $filesystem->getAdapter();

        return $local->getPathPrefix() . $path;
    }
}
