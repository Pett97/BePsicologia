<?php

namespace Tests\Unit\Models;

use App\Models\City;
use App\Models\FixedSchedule;
use App\Models\State;
use App\Models\User;
use Tests\TestCase;

class FixedScheduleTest extends TestCase
{
    private FixedSchedule $fs;
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
        $this->fs = new FixedSchedule([
            'psychologist_id' => $this->user->id,
            'day_of_week' => 4,
            'start_time' => "2024-04-04 13:00:33",
            'end_time' => "2024-04-04 19:00:45"
        ]);
        $this->fs->save();
    }

    public function test_create_a_new_fixedSchedule(): void
    {
        $this->assertCount(1, FixedSchedule::all());
    }
}
