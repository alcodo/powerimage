<?php

namespace Alcodo\PowerImage\Utilities;

class ParamsHelper
{
    /**
     * It converts a string to params.
     *
     * @param $prefix
     * @return array
     */
    public static function getParamsFromPrefix($prefix)
    {
        if (empty($prefix)) {
            return [];
        }

        $directories = explode('/', $prefix);

        $posibleParamString = end($directories);

        $possibleAttributeWithValue = explode(',', $posibleParamString);

        $resultParams = [];

        foreach ($possibleAttributeWithValue as $attributeWithValue) {
            if (strpos($attributeWithValue, '_') !== false) {
                list($attribute, $value) = explode('_', $attributeWithValue);
                $resultParams[$attribute] = $value;
            }
        }

        return $resultParams;
    }

    /**
     * Is removes params from string.
     *
     * @param string $prefix
     * @return string
     */
    public static function getPrefixWithoutParams($prefix)
    {
        $directories = explode('/', $prefix);

        // remove last type of directory
        array_pop($directories);

        return implode('/', $directories);
    }
}
