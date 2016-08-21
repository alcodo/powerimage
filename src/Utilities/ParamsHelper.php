<?php

namespace Alcodo\PowerImage\Utilities;

class ParamsHelper
{

    /**
     * It converts a string to params
     *
     * @param $prefix
     * @return array
     */
    static public function getParamsFromPrefix($prefix)
    {
        if(empty($prefix)){
            return [];
        }

        $directories = explode('/', $prefix);

        $posibleParamString = end($directories);

        $possibleAttributeWithValue = explode(',', $posibleParamString);

        $resultParams = array();

        foreach ($possibleAttributeWithValue as $attributeWithValue){
            list($attribute, $value) = explode('_', $attributeWithValue);
            $resultParams[$attribute] = $value;
        }

        return $resultParams;

//        $posibleParamString
//
//        $directories


        return $prefix;

    }
}