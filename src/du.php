<?php

namespace App\du;

use function Php\Immutable\Fs\Trees\trees\isDirectory;
use function Php\Immutable\Fs\Trees\trees\reduce;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;
use function Php\Immutable\Fs\Trees\trees\getChildren;

function getFileSize($file)
{
    return getMeta($file)['size'];
}

function getDirSize($dir)
{
    $size = array_map(function ($node) {
        print_r($node);
        if (isDirectory($node)) {
            $children = getChildren($node);
            return getDirSize($children);
        }

        return getFileSize($node);
    }, $dir);

    return array_sum($size);
}

function du($tree)
{
    $children = getChildren($tree);
    $result = array_reduce($children, function ($acc, $node) {
        var_dump($node);
        $name = getName($node);
        if (isDirectory($node)) {
            $size = getDirSize($node);
        } else {
            $size = getFileSize($node);
        }

        return $acc[] = [$name => $size];
    }, $acc = []);

    return $result;
}
