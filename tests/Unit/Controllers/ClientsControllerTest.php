<?php

namespace Tests\Unit\Controllers;

use App\Models\City;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;
use Tests\TestCase;

class ClientsControllerTest extends ControllerTestCase
{
    private State $state;
    private City $city;
    private Insurance $insurance;

    public function setUp(): void
    {
        parent::setUp();

        $this->state = new State(['name' => 'Parana']);
        $this->state->save();

        $this->city = new City([
            'name' => 'Guarapuava',
            'state_id' => $this->state->id
        ]);
        $this->city->save();

        $this->insurance = new Insurance(['name' => 'ConvenioTeste']);
        $this->insurance->save();
    }

    public function test_list_all_clients(): void
    {
        $clients = [
            new Client([
                'name' => "ClienteTeste",
                'phone' => "022345678",
                'insurance_id' => $this->insurance->id,
                'street_name' => "nova brasilia",
                'number' => 285,
                'city_id' => $this->city->id
            ]),
            new Client([
                'name' => "ClienteTeste2",
                'phone' => "022345678",
                'insurance_id' => $this->insurance->id,
                'street_name' => "California",
                'number' => 285,
                'city_id' => $this->city->id
            ])
        ];

        foreach ($clients as $client) {
            $client->save();
        }


        $response = $this->get(action: 'index', controller: 'App\Controllers\ClientsController');
        foreach ($clients as $client) {
            $this->assertMatchesRegularExpression("/{$client->name}/", $response);
        }
    }
}
