<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Flatten\flatten;

class FlattenTest extends TestCase
{
    public function testFlatten()
    {
        $expected1 = [];
        $data1 = [];
        $this->assertEquals($expected1, flatten($data1));

        $expected2 = [1, 2, 3, 5, 4, 3, 2];
        $data2 = [1, 2, [3, 5], [[4, 3], 2]];
        $this->assertEquals($expected2, flatten($data2));
    }
}
