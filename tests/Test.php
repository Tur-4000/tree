<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use ArrayObject;

use function Php\Immutable\Fs\Trees\trees\mkdir;
use function Php\Immutable\Fs\Trees\trees\mkfile;
use function Php\Immutable\Fs\Trees\trees\getChildren;
use function App\removeFirstLevel\removeFirstLevel;
use function App\generator\generator;
use function App\tree\compressImages;
use function App\downcaseFileNames\downcaseFileNames;
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

    // test generator()
    public function testGenerator()
    {
        $tree = [
            'name' => 'php-package',
            'children' => [
                ['name' => 'Makefile', 'meta' => [], 'type' => 'file'],
                ['name' => 'README.md', 'meta' => [], 'type' => 'file'],
                [
                    'name' => 'dist',
                    'children' => [],
                    'meta' => [],
                    'type' => 'directory'
                ],
                [
                    'name' => 'tests',
                    'children' => [
                        [
                            'name' => 'test.php',
                            'meta' => ['type' => 'text/php'],
                            'type' => 'file'
                        ]
                    ],
                    'meta' => [],
                    'type' => 'directory'
                ],
                [
                    'name' => 'src',
                    'children' => [
                        [
                            'name' => 'index.php',
                            'meta' => ['type' => 'text/php'],
                            'type' => 'file'
                        ]
                    ],
                    'meta' => [],
                    'type' => 'directory'
                ],
                [
                    'name' => 'phpunit.xml',
                    'meta' => ['type' => 'text/xml'],
                    'type' => 'file'
                ],
                [
                    'name' => 'assets',
                    'children' => [
                        [
                            'name' => 'util',
                            'children' => [
                                [
                                    'name' => 'cli',
                                    'children' => [
                                        [
                                            'name' => 'LICENSE',
                                            'meta' => [],
                                            'type' => 'file'
                                        ]
                                    ],
                                    'meta' => [],
                                    'type' => 'directory'
                                ]
                            ],
                            'meta' => [],
                            'type' => 'directory'
                        ]
                    ],
                    'meta' => ['owner' => 'root', 'hidden' => false],
                    'type' => 'directory'
                ]
            ],
            'meta' => ['hidden' => true],
            'type' => 'directory'
        ];

        $actual = generator();

        $this->assertEquals($tree, $actual);
    }

    // test compressImages()
    public function testCompressImages1()
    {
        $tree = mkdir('my documents', [
                    mkdir('documents.jpg'),
                    mkfile('avatar.jpg', ['size' => 100]),
                    mkfile('passport.jpg', ['size' => 200]),
                    mkfile('family.jpg', ['size' => 150]),
                    mkfile('addresses', ['size' => 125]),
                    mkdir('presentations')
            ], [ 'test' => 'haha']);

        $newTree = compressImages($tree);

        $expectation = [
            'name' => 'my documents',
            'children' => [
                ['name' => 'documents.jpg', 'children' => [], 'meta' => [], 'type' => 'directory'],
                ['name' => 'avatar.jpg', 'meta' => ['size' => 50], 'type' => 'file'],
                ['name' => 'passport.jpg', 'meta' => ['size' => 100], 'type' => 'file'],
                ['name' => 'family.jpg', 'meta' => ['size' => 75], 'type' => 'file'],
                ['name' => 'addresses', 'meta' => ['size' => 125], 'type' => 'file'],
                ['name' => 'presentations', 'children' => [], 'meta' => [], 'type' => 'directory']
            ],
            'meta' => ['test' => 'haha'],
            'type' => 'directory'
        ];

        $this->assertEquals($expectation, $newTree);
    }

    // test downcaseFileNames()
    public function testBeImmutable()
    {
        $tree = mkdir('/', [
          mkdir('eTc', [
            mkdir('NgiNx'),
            mkdir('CONSUL', [
              mkfile('config.json'),
            ]),
          ]),
          mkfile('hOsts'),
        ]);

        $obj = new ArrayObject($tree);
        $origin = $obj->getArrayCopy();

        downcaseFileNames($tree);

        $this->assertEquals($tree, $origin);
    }

    public function testDowncaseFileNames()
    {
        $tree = mkdir('/', [
          mkdir('eTc', [
            mkdir('NgiNx'),
            mkdir('CONSUL', [
              mkfile('config.JSON'),
            ]),
          ]),
          mkfile('hOsts'),
        ]);

        $actual = downcaseFileNames($tree);
        $expected = [
          'children' => [
            [
              'children' => [
                [
                  'children' => [],
                  'meta' => [],
                  'name' => 'NgiNx',
                  'type' => 'directory',
                ],
                [
                  'children' => [['meta' => [], 'name' => 'config.json', 'type' => 'file']],
                  'meta' => [],
                  'name' => 'CONSUL',
                  'type' => 'directory',
                ],
              ],
              'meta' => [],
              'name' => 'eTc',
              'type' => 'directory',
            ],
            ['meta' => [], 'name' => 'hosts', 'type' => 'file'],
          ],
          'meta' => [],
          'name' => '/',
          'type' => 'directory',
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testReturnFullCopy()
    {
        $tree = mkdir('/', [
          mkdir('eTc', [
            mkdir('NgiNx', [], ['size' => 4000]),
            mkdir('CONSUL', [
              mkfile('config.JSON', ['uid' => 0]),
            ]),
          ]),
          mkfile('hOsts'),
        ]);

        $actual = downcaseFileNames($tree);
        $expected = [
          'children' => [
            [
              'children' => [
                [
                  'children' => [],
                  'meta' => ['size' => 4000],
                  'name' => 'NgiNx',
                  'type' => 'directory',
                ],
                [

                  'children' => [['meta' => ['uid' => 0], 'name' => 'config.json', 'type' => 'file']],
                  'meta' => [],
                  'name' => 'CONSUL',
                  'type' => 'directory',
                ],
              ],
              'meta' => [],
              'name' => 'eTc',
              'type' => 'directory',
            ],
            ['meta' => [], 'name' => 'hosts', 'type' => 'file'],
          ],
          'meta' => [],
          'name' => '/',
          'type' => 'directory',
        ];

        $this->assertEquals($expected, $actual);
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
