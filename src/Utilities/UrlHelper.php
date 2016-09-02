<?php

namespace Alcodo\PowerImage\Utilities;

class UrlHelper
{
    /**
     * Converts storage path to url path
     *
     * @param $storagePath
     * @return string
     */
    public static function getPowerImageUrlPath($storagePath)
    {
        return '/powerimage' . $storagePath;
    }
}
