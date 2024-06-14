<?php

namespace Tests\Unit\Models;

use App\Models\Car;
use PHPUnit\Framework\TestCase;

class CarTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $car = new Car(name:"uno");

        $this->assertEquals("UNO", $car->getName());
    }

    public function test_dont_create_without_name(): void
    {
        $car = new Car(name:'');

        $hasErrors = $car->hasErrors();
        $this->assertTrue($hasErrors);
    }
}
