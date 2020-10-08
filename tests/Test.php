<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function App\removeFirstLevel\removeFirstLevel;
use function App\du\du;
use function App\du\getDirSize;

class Test extends TestCase
{
    // test removeFirstLevel()
    public function testEmpty()
    {
        $tree = [];
        $result = [];
        $this->assertEquals($result, removeFirstLevel($tree));
    }

    public function testFlat()
    {
        $tree = [1, 100, 3];
        $result = [];
        $this->assertEquals($result, removeFirstLevel($tree));
    }

    public function testRemoveFirstLevel()
    {
        $tree = [[1, [3, 2]], 2, [3, 5], 2, [130, [1, [550, 10]]]];
        $result = [1, [3, 2], 3, 5, 130, [1, [550, 10]]];
        $this->assertEquals($result, removeFirstLevel($tree));
    }

    // test du()
    // public function testDirSize()
    // {
    //     $tree = mkdir('/', [
    //         mkdir('etc', [
    //             mkdir('apache'),
    //             mkdir('nginx', [
    //                 mkfile('nginx.conf', ['size' => 800]),
    //             ]),
    //             mkdir('consul', [
    //                 mkfile('config.json', ['size' => 1200]),
    //                 mkfile('data', ['size' => 8200]),
    //                 mkfile('raft', ['size' => 80]),
    //             ]),
    //         ]),
    //         mkfile('hosts', ['size' => 3500]),
    //         mkfile('resolve', ['size' => 1000]),
    //     ]);

    //     $expected = 13880;

    //     $this->assertEquals($expected, getDirSize($tree));
    // }

    // public function testDuTree()
    // {
    //     $tree = mkdir('/', [
    //         mkdir('etc', [
    //             mkdir('apache'),
    //             mkdir('nginx', [
    //                 mkfile('nginx.conf', ['size' => 800]),
    //             ]),
    //             mkdir('consul', [
    //                 mkfile('config.json', ['size' => 1200]),
    //                 mkfile('data', ['size' => 8200]),
    //                 mkfile('raft', ['size' => 80]),
    //             ]),
    //         ]),
    //         mkfile('hosts', ['size' => 3500]),
    //         mkfile('resolve', ['size' => 1000]),
    //     ]);
        
    //     $expected = [
    //         ['etc', 10280],
    //         ['hosts', 3500],
    //         ['resolve', 1000],
    //     ];

    //     $this->assertEquals($expected, du($tree));
    // }

    // public function testDuChildren()
    // {
    //     $tree = mkdir('/', [
    //         mkdir('etc', [
    //             mkdir('apache'),
    //             mkdir('nginx', [
    //                 mkfile('nginx.conf', ['size' => 800]),
    //             ]),
    //             mkdir('consul', [
    //                 mkfile('config.json', ['size' => 1200]),
    //                 mkfile('data', ['size' => 8200]),
    //                 mkfile('raft', ['size' => 80]),
    //             ]),
    //         ]),
    //         mkfile('hosts', ['size' => 3500]),
    //         mkfile('resolve', ['size' => 1000]),
    //     ]);

    //     $expected = [
    //         ['consul', 9480],
    //         ['nginx', 800],
    //         ['apache', 0],
    //     ];

    //     $this->assertEquals($expected, du(getChildren($tree)[0]));
    // }
}
