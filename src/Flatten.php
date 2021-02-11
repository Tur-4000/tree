<?php

namespace App\Flatten;

function iter($col, $acc = [])
{
    if (!is_array($col)) {
        $acc[] = $col;
    } else {
        foreach ($col as $item) {
            $acc += iter($item, $acc);
        }
    }

    return $acc;
}

function flatten(array $collection): array
{
    return iter($collection);
}
