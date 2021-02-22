<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

use function App\Itinerary\itinerary;

class ItineraryTest extends TestCase
{
    public function testItinerary()
    {
        $tree = ['Moscow', [
            ['Smolensk'],
            ['Yaroslavl'],
            ['Voronezh', [
                ['Liski'],
                ['Boguchar'],
                ['Kursk', [
                    ['Belgorod', [
                        ['Borisovka'],
                    ]],
                    ['Kurchatov'],
                ]],
            ]],
            ['Ivanovo', [
                ['Kostroma'], ['Kineshma'],
            ]],
            ['Vladimir'],
            ['Tver', [
                ['Klin'], ['Dubna'], ['Rzhev'],
            ]],
        ]];

        $expected1 = ['Dubna', 'Tver', 'Moscow', 'Ivanovo', 'Kostroma'];
        $this->assertEquals($expected1, itinerary($tree, 'Dubna', 'Kostroma'));
        $expected2 = ['Borisovka', 'Belgorod', 'Kursk', 'Kurchatov'];
        $this->assertEquals($expected2, itinerary($tree, 'Borisovka', 'Kurchatov'));
    }
}
