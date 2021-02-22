<?php

namespace App\Itinerary;

function itinerary(array $tree, string $startPoint, string $endPoint): array
{
    $iter = function ($node, $point, $path, $acc) use (&$iter) {
        if (!is_array($node)) {
            if ($node !== $point) {
                return $acc;
            }
            $newPath = ($path[] = $node);
            $acc[] = $newPath;
            return $acc;
        }

        if (count($node) > 1) {
            [$name, $rest] = $node;
            $newPath = ($path[] = $name);
        } else {
            var_dump($path);
            $rest = $node;
            $newPath = ($path[] = $rest);
        }

        return array_reduce($rest, function ($newAcc, $node) use ($point, $newPath, $iter) {
            return $iter($node, $point, $newPath, $newAcc);
        }, $acc);
    };

    $trak1 = $iter($tree, $startPoint, [], []);
    $trak2 = $iter($tree, $endPoint, [], []);

    return array_merge($trak1, array_reverse($trak2));
}
