<?php

namespace Alcodo\PowerImage\Controllers;

use Alcodo\PowerImage\Jobs\ResizeImage;
use Alcodo\PowerImage\Utilities\ParamsHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PowerImageController extends Controller
{
    use DispatchesJobs;

    public function showFile($file)
    {
        if (Storage::disk('powerimage')->exists($file) === false) {
            abort(404);
        }

        return $this->showImageFile($file);
    }

    public function show($prefix, $file)
    {
        $absoluteFilename = $prefix.'/'.$file;

        // orignal or resized image already available
        if (Storage::disk('powerimage')->exists($absoluteFilename)) {
            return $this->showImageFile($absoluteFilename);
        }

        $params = ParamsHelper::getParamsFromPrefix($prefix);

        // original image not available
        if (empty($params)) {
            abort(404);
        }

        // resize image
        $prefixWithoutParams = ParamsHelper::getPrefixWithoutParams($prefix);
        $rs = new ResizeImage($prefixWithoutParams, $params);
        $resizedFilepath = $this->dispatch($rs);

        // output resized image
        return $this->showImageFile($resizedFilepath);
    }

    protected function showImageFile($file)
    {
        $headers['Content-Type'] = Storage::disk('powerimage')->mimeType($file);
        $headers['Content-Length'] = Storage::disk('powerimage')->size($file);
        $headers['Cache-Control'] = 'max-age=7776000, public';
        $headers['Expires'] = date_create('+90 days')->format('D, d M Y H:i:s').' GMT';
        $headers['PowerImage'] = 'Compressed';

        return Response::make(Storage::disk('powerimage')->get($file), 200, $headers);
    }
}
