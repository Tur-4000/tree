<?php

namespace App\du;

use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\reduce;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\getChildren;

function getFileSize(array $file): int
{
    return getMeta($file)['size'];
}

function getDirectorySize(array $tree): int
{
    $size = 0;
    $children = getChildren($tree);

    foreach ($children as $node) {
        if (!isDirectory($node)) {
            $size += getFileSize($node);
        } else {
            $size += getDirectorySize($node);
        }
    }

    return $size;
}

function du(array $tree): array
{
    $children = getChildren($tree);

    $du = array_map(function ($child) {
        return isDirectory($child) ?
            [getName($child), getDirectorySize($child)] :
            [getName($child), getFileSize($child)];
    }, $children);

    usort($du, fn($a, $b) => $b[1] <=> $a[1]);

    return $du;
}
