<?php

namespace Tests\Unit\Lib;

use Tests\TestCase;
use Lib\Paginator;
use App\Models\Brand;
use App\Models\Car;

class PaginatorTest extends TestCase
{
    private Paginator $paginator;
    /** @var mixed[] $cars */
    private array $cars;
    /** @var mixed[] $brands */
    private array $brands;

    public function setUp(): void
    {
        parent::setUp();
        for ($i = 0; $i < 10; $i++) {
            $car = new Car(name: "CarTest" . $i);
            $car->save();
            $this->cars[] = $car;
        }
        for ($i = 0; $i < 10; $i++) {
            $brand = new Brand(name: "BrandTest" . $i);
            $brand->save();
            $this->brands[] = $brand;
        }
        $this->paginator = new Paginator(Car::class, 1, 10, 'cars', ['name']);
    }

    public function test_total_of_registers_of_cars(): void
    {
        $this->assertEquals(10, $this->paginator->totalOfRegisters());
    }

    public function test_total_of_pages(): void
    {
        $this->assertEquals(1, $this->paginator->totalOfPages());
    }

    public function test_total_of_pages_when_the_division_is_not_exact(): void
    {
        $car = new Car(name: "CarTest11");
        $car->save();
        $this->paginator = new Paginator(Car::class, 1, 5, 'cars', ['name']);

        $this->assertEquals(3, $this->paginator->totalOfPages());
    }

    public function test_previous_pages(): void
    {
        $this->assertEquals(0, $this->paginator->previousPage());
    }

    public function test_next_pages(): void
    {
        $this->assertEquals(2, $this->paginator->nextPage());
    }

    public function test_has_previous_page(): void
    {
        $this->assertFalse($this->paginator->hasPreviousPage());

        $paginator = new Paginator(Car::class, 2, 5, 'cars', ['name']);
        $this->assertTrue($paginator->hasPreviousPage());
    }

    public function test_has_next_page(): void
    {
        $this->assertTrue($this->paginator->hasNextPage());

        $paginator = new Paginator(Car::class, 2, 5, 'cars', ['name']);
        $this->assertTrue($paginator->hasNextPage());
    }

    public function test_is_page(): void
    {
        $this->assertTrue($this->paginator->isPage(1));
        $this->assertFalse($this->paginator->isPage(2));
    }

    public function test_entries_info(): void
    {
        $entriesInfo = 'Mostrando 1 - 10 de 10';
        $this->assertEquals($entriesInfo, $this->paginator->entriesInfo());
    }

    public function test_register_return_all(): void
    {
        $this->assertCount(10, $this->paginator->registers());

        $paginator = new Paginator(Car::class, 1, 10, 'cars', ['name']);
        $this->assertEquals(sizeof($this->cars), 10);

        $paginator = new Paginator(Brand::class, 1, 10, 'brands', ['name']);
        $this->assertEquals(sizeof($this->brands), 10);
    }
}
