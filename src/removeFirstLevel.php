<?php

namespace App\removeFirstLevel;

function removeFirstLevel(array $tree): array
{
    if (empty($tree)) {
        return [];
    }

    $filtered = \array_filter($tree, fn ($node) => \is_array($node));

    return array_merge(...$filtered);

    // return array_reduce($filtered, function ($acc, $node) {
    //     foreach ($node as $value) {
    //         $acc[] = $value;
    //     }
    //     return $acc;
    // }, $acc = []);
}
