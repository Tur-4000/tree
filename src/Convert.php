<?php

namespace App\Convert;

function iter($coll)
{
    // foreach ($coll as $item) {
    //     [$key, $val] = $item;
    //     if (!is_array($val)) {
    //         $acc[$key] = $val;
    //     } else {
    //         $acc[$key] = iter($val);
    //     }
    // }

    // return $acc;

    return array_reduce($coll, function ($acc, $item) {
        [$key, $val] = $item;
        if (!is_array($val)) {
            $acc[$key] = $val;
        } else {
            $acc[$key] = iter($val);
        }
        return $acc;
    }, []);
}

function convert(array $collection): array
{
    if (empty($collection)) {
        return [];
    }

    return iter($collection);
}
