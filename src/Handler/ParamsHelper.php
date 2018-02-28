<?php

namespace Alcodo\PowerImage\Handler;

class ParamsHelper
{
    /**
     * It converts a string to params.
     *
     * from:
     * w=300&h=300&fit=crop
     * w:200,h:200
     *
     * to:
     * ['w' => 300, 'h' => 300, 'fit' => 'crop']
     *
     * @param $prefix
     * @return array
     */
    public static function parseToArray($parameterString)
    {
        $result = [];
        $eachType = explode('&', $parameterString);

        foreach ($eachType as $typeWithValue) {
            list($parameter, $value) = explode('=', $typeWithValue);
            $result[$parameter] = $value;
        }

        return $result;
    }

    /**
     * from:
     * images/car_w=300&h=200.jpg
     *
     * to:
     * w=300&h=200
     *
     * @param $path
     * @param $fileextension
     * @return bool
     */
    public static function getParameterString($path, $fileextension)
    {
        preg_match('/_(.*?).' . $fileextension . '/', $path, $match);

        if (!isset($match[1]) || empty($match[1])) {
            return false;
        }

        return $match[1];
    }
}