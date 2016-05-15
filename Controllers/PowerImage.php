<?php namespace Alcodo\PowerImage\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

class PowerImage extends Controller
{

    public function show(\League\Glide\Server $server, $path)
    {
        return $server->outputImage($path, Input::query());
    }

}