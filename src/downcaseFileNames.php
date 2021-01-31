<?php

namespace App\downcaseFileNames;

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\isFile;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\getChildren;

// BEGIN (write your solution here)
function downcaseFileNames(array $tree): array
{
    $name = getName($tree);
    $meta = getMeta($tree);
    $childrens = getChildren($tree);

    $newChildren = array_map(function ($child) {
        if (isFile($child)) {
            $newName = strtolower(getName($child));
            $meta = getMeta($child);

            return mkfile($newName, $meta);
        }

        return downcaseFileNames($child);
    }, $childrens);

    return mkdir($name, $newChildren, $meta);

    /**
     * Решение учителя
     *
     * $name = getName($node);
     *
     * if (isFile($node)) {
     *   $newName = strtolower(getName($node));
     *   return mkfile($newName, getMeta($node));
     * }
     *
     * $updatedChildren = array_map(fn($child) => downcaseFileNames($child), getChildren($node));
     *
     * return mkdir($name, $updatedChildren, getMeta($node));
     */
}
