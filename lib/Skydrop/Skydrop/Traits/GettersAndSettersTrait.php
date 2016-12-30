<?php

namespace Skydrop\Traits;

trait GettersAndSettersTrait
{
    public function getFromArray($key, $array, $default)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $default;
    }

    function __call($method, $params) {

        $var = lcfirst(substr($method, 3));

        if (substr($method, 0, 3) == 'get') {
            return $this->$var;
        }

        if (substr($method, 0, 3) == 'set') {
            $this->$var = $params[0];
        }
    }
}
