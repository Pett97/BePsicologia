<?php

namespace Tests\Unit\Models;

use App\Models\City;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $city = new City(
            name:"Tangamandapio",
            idState:0
        );

        $this->assertEquals("TANGAMANDAPIO", $city->getName());
    }

    public function test_dont_create_without_name(): void
    {
        $city = new City(name:'');

        $hasErrors = $city->hasErrors();
        $this->assertFalse($hasErrors);
    }
}
