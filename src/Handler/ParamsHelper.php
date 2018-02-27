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
}