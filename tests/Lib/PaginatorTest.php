<?php

namespace Tests\Unit\Lib;

use Tests\TestCase;
use Lib\Paginator;
use App\Models\City;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;

class PaginatorTest extends TestCase
{
    private Client $client;
    private State $state;
    private City $city;
    private Insurance $insurance;
    private Paginator $paginator;

    public function setUp(): void
    {
        parent::setUp();

        $this->state = new State([
            'name' => 'Parana'
        ]);
        $this->state->save();

        $this->city = new City([
            'name' => 'Guarapuava',
            'state_id' => $this->state->id
        ]);
        $this->city->save();

        $this->insurance = new Insurance([
            'name' => 'ConvenioTeste'
        ]);
        $this->insurance->save();

        $clients = [];
        for ($i = 1; $i <= 10; $i++) {
            $client = new Client([
                'name' => "ClienteTeste" . $i,
                'phone' => "022345678",
                'insurance_id' => $this->insurance->id,
                'street_name' => "nova brasilia",
                'number' => 285,
                'city_id' => $this->city->id
            ]);
            array_push($clients, $client);
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
        $this->client = new Client([
            'name' => "ClienteTeste",
            'phone' => "022345678",
            'insurance_id' => $this->insurance->id,
            'street_name' => "nova brasilia",
            'number' => 285,
            'city_id' => $this->city->id
        ]);
        $this->client->save();

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
        $this->assertFalse($this->paginator->hasNextPage());
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
