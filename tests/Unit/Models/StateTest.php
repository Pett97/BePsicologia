<?php

namespace Tests\Unit\Models;

use App\Models\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $state = new State(
            name:"California"
        );

        $this->assertEquals("CALIFORNIA", $state->getName());
    }

    public function test_dont_create_withOut_name(): void
    {
        $state = new State(name:'');

        $hasErrors = $state->hasErrors();
        $this->assertFalse($hasErrors);
    }
}
