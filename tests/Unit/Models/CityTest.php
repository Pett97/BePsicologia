<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\State;
use Tests\TestCase;

class CityTest extends TestCase
{
    private State $state;
    private City $city;

    public function setUp(): void
    {
        parent::setUp();

        $this->state = new State([
            'name' => 'Parana'
        ]);
        $this->state->save();

        $this->city = new City([
            'name' => 'Guarapuava',
            'state_id' => $this->state->id //pego o ID do estado que acabei de criar
        ]);
        $this->city->save();
    }

    public function test_should_create_new_city(): void
    {
        $this->assertTrue($this->city->save());
        $this->assertCount(1, City::all());
    }

    public function test_all_should_return_all_cities(): void
    {
        $cities[] = $this->city;
        $all = City::all();
        $this->assertCount(1, $all);
        $this->assertEquals($cities, $all);
    }
}
