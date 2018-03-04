<?php

namespace Alcodo\PowerImage\Handler;

/**
 * Class Exception.
 *
 * Check if block is a exception
 */
class ExceptionCheck
{

    public static function check($path, $rules)
    {
        $patterns_quoted = preg_quote($rules, '/');
        $to_replace = [
            '/(\r\n?|\n)/', // newlines
            '/\\\\\*/',     // wildcard
        ];
        $replacements = [
            '|',
            '.*',
        ];

        $regexpPatter = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';

        return (bool)preg_match($regexpPatter, $path);
    }

}
