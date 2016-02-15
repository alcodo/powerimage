<?php

Route::get('/uploads/images/{path}', function (\League\Glide\Server $server, $path) {
    return $server->outputImage($path, Input::query());
})->where('path', '.+');