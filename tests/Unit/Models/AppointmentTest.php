<?php

namespace Tests\Unit\Models;

use App\Models\Appointment;
use App\Models\City;
use App\Models\Client;
use App\Models\Insurance;
use App\Models\State;
use App\Models\User;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    private State $state;
    private City $city;
    private User $user;
    private Insurance $insurance;
    private Client $client;
    private Appointment $appointment;

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

        $this->user = new User([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
            'city_id' => $this->city->id
        ]);
        $this->user->save();

        $this->appointment = new Appointment([
            'psychologist_id' => $this->user->id,
            'date' => "1997-04-04",
            'start_time' => "2024-04-04 13:00:45",
            'end_time' => "2024-04-04 14:00:31",
            'client_id' => $this->client->id
        ]);
        $this->appointment->save();
    }

    public function test_should_create_new_appointment(): void
    {
        $this->assertTrue($this->appointment->save());
        $this->assertCount(1, Appointment::all());
    }
}
