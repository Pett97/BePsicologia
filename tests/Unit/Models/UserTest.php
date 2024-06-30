<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\State;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    private User $user;
    private User $user2;
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
            'state_id' => $this->state->id
        ]);
        $this->city->save();
        $this->user = new User([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
            'city_id' => $this->city->id
        ]);
        $this->user->save();

        $this->user2 = new User([
            'name' => 'Admin',
            'email' => 'admin2@example.com',
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
            'city_id' => $this->city->id
        ]);
        $this->user2->save();
    }

    public function test_should_create_new_user(): void
    {
        $this->assertCount(2, User::all());
    }

    public function test_destroy_should_remove_the_user(): void
    {
        $this->user->destroy();
        $this->assertCount(1, User::all());
    }
}
