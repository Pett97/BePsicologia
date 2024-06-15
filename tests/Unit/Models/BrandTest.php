<?php

namespace Tests\Unit\Models;

use App\Models\Brand;
use PHPUnit\Framework\TestCase;

class BrandTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $brand = new Brand(name:"fOrD");

        $this->assertEquals("FORD", $brand->getName());
    }

    public function test_dont_create_without_name(): void
    {
        $brand = new Brand(name:'');

        $hasErrors = $brand->hasErrors();
        $this->assertTrue($hasErrors);
    }
}
