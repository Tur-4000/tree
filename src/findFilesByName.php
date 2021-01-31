<?php

namespace App\findFilesByName;

use function Php\Immutable\Fs\Trees\trees\isFile;
use function Php\Immutable\Fs\Trees\trees\getName;
use function Php\Immutable\Fs\Trees\trees\getChildren;

// BEGIN (write your solution here)
function iter($node, $text, $parents, $acc)
{
    $name = getName($node);
    $newParents = $name === '/' ? '' : "{$parents}/{$name}";

    if (isFile($node)) {
        if (strpos($name, $text) === false) {
            return $acc;
        }

        $acc[] = $newParents;

        return $acc;
    }

    $children = getChildren($node);

    $result = array_reduce($children, function ($newAcc, $child) use ($text, $newParents) {
        return iter($child, $text, $newParents, $newAcc);
    }, $acc);

    return $result;
}

function findFilesByName(array $tree, string $text)
{
    return iter($tree, $text, '', []);
}
// END
