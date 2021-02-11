<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Convert\convert;

class ConvertTest extends TestCase
{
    public function testFlatten()
    {
        $expected1 = [];
        $data1 = [];
        $this->assertEquals($expected1, convert($data1));

        $expected2 = ['key' => 'value'];
        $data2 = [['key', 'value']];
        $this->assertEquals($expected2, convert($data2));

        $expected3 = ['key' => ['key2' => 'anotherValue'], 'key2' => 'value2'];
        $data3 = [
            ['key', [['key2', 'anotherValue']]],
            ['key2', 'value2']
        ];
        $this->assertEquals($expected3, convert($data3));
    }
}
