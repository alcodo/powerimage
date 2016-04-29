<?php namespace Alcodo\PowerImage\Controllers;

class PowerImage extends Controller
{

    public function show(\League\Glide\Server $server, $path)
    {
        return $server->outputImage($path, Input::query());
    }

}