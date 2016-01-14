<?php namespace Alcodo\PowerImage\Controllers;

use Alcodo\PowerImage\Jobs\CreateImage;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    /**
     * Not implementet
     *
     * @return Response
     */
    public function show($image)
    {
        $fullPath = CreateImage::UploadDirectory . $image;
        if (Storage::exists($fullPath) === false) {
            return abort(404);
        }

        $image = Storage::get($fullPath);

        $type = 'image/png';
        return response($image)->header('Content-Type', $type);
    }
}


