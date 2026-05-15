<?php

namespace Kopokopo\SDK\Helpers;

class K2Utils
{
    static public function camelize($string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $string))));
    }

    static public function deepCamelizeKeys($array): ?array {
        if(!is_array($array)) return $array;

        $camelizedArray = [];

        foreach($array as $key => $value) {
            $camelizedKey = K2Utils::camelize($key);
            $camelizedArray[$camelizedKey] = is_array($value) ? K2Utils::deepCamelizeKeys($value) : $value;
        }

        return $camelizedArray;
    }
}