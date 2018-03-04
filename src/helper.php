<?php

use Alcodo\PowerImage\Facades\PowerImage;

/**
 * It returns a powerimage storage path.
 *
 * @param string $path
 * @param array  $params
 *
 * @return string
 */
function powerimage(string $path, array $params): string
{
    return PowerImage::path($path, $params);
}
