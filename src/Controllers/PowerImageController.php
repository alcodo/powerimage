<?php

namespace Alcodo\PowerImage\Controllers;

use Alcodo\PowerImage\Jobs\ResizeImage;
use Alcodo\PowerImage\Utilities\ParamsHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class PowerImageController extends Controller
{
    use DispatchesJobs;

    public function showFile($file)
    {
        $file = 'powerimage/' . $file;

//        if (Storage::disk('powerimage')->exists($file)) {
        if (Storage::exists($file) === false) {
            abort(404);
        }

        return $this->showImageFile($file);
    }

    public function show($prefix, $file)
    {

        $absoluteFilename = 'powerimage/' . $prefix . '/' . $file;

        // orignal or resized image already available
        if (Storage::disk('powerimage')->exists($absoluteFilename)) {
            dd(Storage::disk('powerimage')->get($absoluteFilename));
            return Storage::disk('powerimage')->get($absoluteFilename);
        }

        $params = ParamsHelper::getParamsFromPrefix($prefix);

        // original image not available
        if ($params) {
            abort(404);
        }

        $image = new CreateImage($imageupload, null, 'gallery/');
        $filepath = $this->dispatch(new ResizeImage());


        return $absoluteFilename;
        return $prefix . '/' . $file;
        return 'show';
    }

    protected function showImageFile($file)
    {
        $headers['Content-Type'] = Storage::mimeType($file);
        $headers['Content-Length'] = Storage::size($file);
        $headers['Cache-Control'] = 'max-age=7776000, public';
        $headers['Expires'] = date_create('+90 days')->format('D, d M Y H:i:s') . ' GMT';
        $headers['PowerImage'] = 'Compressed';

        return Response::make(Storage::get($file), 200, $headers);
    }

    //    public function show($directory, $type, $filename, $fileextension){
//        $filepath = $directory . $type . $filename . $fileextension;
//        dd($filepath);
//    }

//    public function show($filepath, \League\Glide\Server $server)
//    {
//        $params = Input::query();
//
//        // original image output
//        if (empty($params)) {
//            return $this->showOriginalImage($filepath, $server);
//        }
//
//        if ($server->cacheFileExists($filepath, $params) === false) {
//            // resize file must created
//            $cacheFile = $this->generateImage($filepath, $params, $server);
//        } else {
//            // resize file exists
//            $cacheFile = $server->makeImage($filepath, $params);
//        }
//
//        // response
//        $file = $server->getCache();
//        $headers = [];
//        $headers['Content-Type'] = $file->getMimetype($cacheFile);
//        $headers['Content-Length'] = $file->getSize($cacheFile);
//        $headers['Cache-Control'] = 'max-age=2592000, public';
//        $headers['Expires'] = date_create('+30 days')->format('D, d M Y H:i:s').' GMT';
//        $headers['PowerImage'] = 'Compressed';
//
//        return Response::make($file->read($cacheFile), 200, $headers);
//    }
//
//    protected function showOriginalImage($filepath, \League\Glide\Server $server)
//    {
//        $filepath = $server->getSourcePath($filepath);
//        $file = $server->getCache();
//
//        if ($file->has($filepath) === false) {
//            abort(404);
//        }
//
//        // response
//        $headers = [];
//        $headers['Content-Type'] = $file->getMimetype($filepath);
//        $headers['Content-Length'] = $file->getSize($filepath);
//        $headers['Cache-Control'] = 'max-age=7776000, public';
//        $headers['Expires'] = date_create('+90 days')->format('D, d M Y H:i:s').' GMT';
//        $headers['PowerImage'] = 'Original';
//
//        return Response::make($file->read($filepath), 200, $headers);
//    }
//
//    /**
//     * Returns a absoulte filepath from path.
//     *
//     * @param $filepath
//     * @param \League\Glide\Server $server
//     * @return string
//     */
//    protected function getAbsoulteFilepath($filepath, \League\Glide\Server $server)
//    {
//        /** @var Filesystem $filesystem */
//        $filesystem = $server->getSource();
//
//        /** @var Local $local */
//        $local = $filesystem->getAdapter();
//
//        return $local->getPathPrefix().$filepath;
//    }
//
//    /**
//     * Image will be resize and optimized.
//     *
//     * @param $filepath
//     * @param $params
//     * @param $server
//     * @return string
//     */
//    private function generateImage($filepath, $params, $server)
//    {
//        // resize image
//        try {
//            $cacheFile = $server->makeImage($filepath, $params);
//        } catch (\Exception $e) {
//            abort(404);
//        }
//        $absoluteFilepath = $this->getAbsoulteFilepath($cacheFile, $server);
//
//        // optimize image
//        $optimizer = app('Approached\LaravelImageOptimizer\ImageOptimizer');
//        $optimizer->optimizeImage($absoluteFilepath, pathinfo($filepath, PATHINFO_EXTENSION));
//
//        return $cacheFile;
//    }
}
