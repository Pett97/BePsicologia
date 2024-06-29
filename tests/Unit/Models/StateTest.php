<?php

namespace Tests\Unit\Models;

use App\Models\State;
use Tests\TestCase;

class StateTest extends TestCase
{
    private State $state;

    public function setUp(): void
    {
        parent::setUp();
        $this->state = new State([
            'name' => 'Parana'
        ]);
        $this->state->save();
    }

    public function test_should_create_new_state(): void
    {
        $this->assertTrue($this->state->save());
        $this->assertCount(1, State::all());
    }

    public function test_all_should_return_all_states(): void
    {
        $states[] = $this->state;
        $all = State::all();
        $this->assertCount(1, $all);
        $this->assertEquals($states, $all);
    }
}
