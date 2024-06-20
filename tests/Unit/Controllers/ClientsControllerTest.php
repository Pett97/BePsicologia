<?php

namespace Tests\Unit\Controllers;

//require "/var/www/core/constants/general.php";

use App\Models\City;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;

class ClientsControllerTest extends ControllerTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $state = new State(name: "Springfield");
        $state->save();

        $city = new City(name: "South Park", idState: $state->getID());
        $city->save();

        $insurance = new Insurance(name: "Test");
        $insurance->save();
    }

    public function test_list_all_clients(): void
    {
        $clients[] = new Client(
            name: "ana",
            phone: "429888534488",
            insurance_id: 1,
            streetName: "rua teste",
            numberHouse: 200,
            city_id: 1
        );
        $clients[] = new Client(
            name: "ana2",
            phone: "429888534488",
            insurance_id: 1,
            streetName: "rua teste",
            numberHouse: 200,
            city_id: 1
        );


        foreach ($clients as $client) {
            $client->save();
        }

        $response  = $this->get(action: "index", controller: "App\Controllers\ClientsController");



        foreach ($clients as $client) {
            $this->assertMatchesRegularExpression("/{$client->getName()}/", $response);
        }
    }
}
