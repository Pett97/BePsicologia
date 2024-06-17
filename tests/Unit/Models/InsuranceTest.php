<?php

namespace Tests\Unit\Models;

use App\Models\Insurance;
use PHPUnit\Framework\TestCase;

class InsuranceTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $insurance = new Insurance(
            name:"NULLPOINTER"
        );

        $this->assertEquals("NULLPOINTER", $insurance->getName());
    }

    public function test_dont_create_without_name(): void
    {
        $insurance = new insurance(name:'');

        $hasErrors = $insurance->hasErrors();
        $this->assertFalse($hasErrors);
    }
}
