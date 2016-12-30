<?php

function mergeArrayByKey($array1, $array2, $key)
{
    $keys = array_map(function($el) use ($key) { return $el->$key; }, $array2);
    $missing = arrayDiffByKey($array1, $key, $keys);

    return array_merge($array2, $missing);
}

function arrayDiffByKey($array1, $key, $keys)
{
    return array_filter(
        $array1,
        function($el) use ($key, $keys) {
            if (!in_array($el->$key, $keys)) {
                return $el;
            }
        }
    );
}
