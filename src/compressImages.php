<?php

namespace App\tree;

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\isFile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getMeta;

function isImage($node): bool
{
    $images = ['.jpg', '.jpeg', '.png', '.gif', '.bmp'];
    $name = getName($node);
    $ext = \substr($name, \stripos($name, '.'));

    return \in_array($ext, $images);
}

function compressImages(array $tree): array
{
    $children = getChildren($tree);

    $newChildren = array_map(function ($node) {
        if (isFile($node) && isImage($node)) {
            $newMeta = getMeta($node);
            $newMeta['size'] = $newMeta['size'] / 2;

            return mkfile(getName($node), $newMeta);
        }
        return $node;
    }, $children);

    return mkdir(getName($tree), $newChildren, getMeta($tree));
}
