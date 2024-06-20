<?php

namespace Tests\Unit\Lib;

use App\Models\City;
use Tests\TestCase;
use Lib\Paginator;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;

class PaginatorTest extends TestCase
{
    private Paginator $paginator;

    public function setUp(): void
    {
        parent::setUp();

        $state = new State(name: "Springfield");
        $state->save();

        $city = new City(name: "South Park", idState: $state->getID());
        $city->save();

        $insurance = new Insurance(name: "Test");
        $insurance->save();

        for ($i = 0; $i < 10; $i++) {
            $client = new Client(
                name: 'ClientTeste' . $i,
                phone: '42988853477',
                insurance_id: 1,
                streetName: 'Rua teste',
                numberHouse: 123,
                city_id: 1
            );
            $client->save();
        }

        $this->paginator = new Paginator(Client::class, 1, 10, 'clients', ['name']);
    }

    public function test_total_of_registers_of_clients(): void
    {
        $this->assertEquals(10, $this->paginator->totalOfRegisters());
    }

    public function test_total_of_pages(): void
    {
        $this->assertEquals(1, $this->paginator->totalOfPages());
    }

    public function test_total_of_pages_when_the_division_is_not_exact(): void
    {
        $client = new Client(
            name: 'ClientTeste',
            phone: '42988853477',
            insurance_id: 1,
            streetName: 'Rua teste',
            numberHouse: 123,
            city_id: 1
        );
        $client->save();

        $this->paginator = new Paginator(Client::class, 1, 5, 'clients', ['name']);

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

        $paginator = new Paginator(Client::class, 2, 5, 'clients', ['name']);
        $this->assertTrue($paginator->hasPreviousPage());
    }

    public function test_has_next_page(): void
    {
        $this->assertTrue($this->paginator->hasNextPage());

        $paginator = new Paginator(Client::class, 2, 5, 'clients', ['name']);
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
    }
}
