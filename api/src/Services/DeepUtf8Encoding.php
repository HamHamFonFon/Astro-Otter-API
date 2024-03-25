<?php

namespace App\Services;

class DeepUtf8Encoding
{
    public function __invoke(string|array|object &$input): array|object|false|string|null
    {
        if (is_string($input)) {
            $input = mb_convert_encoding($input, 'UTF-8', 'ISO-8859-1');
        } else if (is_array($input)) {
            foreach ($input as &$value) {
                self::__invoke($value);
            }

            unset($value);
        } else if (is_object($input)) {
            $vars = array_keys(get_object_vars($input));
            foreach ($vars as $var) {
                self::__invoke($input->$var);
            }
        }
        return $input;
    }
}
