<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;
use Tests\TestCase;

class ClientTest extends TestCase
{
    private State $state;
    private City $city;

    private Insurance $insurance;
    private Client $client;



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

        $this->client = new Client([
            'name' => "ClienteTeste",
            'phone' => "022345678",
            'insurance_id' => $this->insurance->id,
            'street_name' => "nova brasilia",
            'number' => 285,
            'city_id' => $this->city->id
        ]);
        $this->client->save();
    }

    public function test_should_create_new_client(): void
    {
        $this->assertTrue($this->client->save());
        $this->assertCount(1, Client::all());
    }
}
