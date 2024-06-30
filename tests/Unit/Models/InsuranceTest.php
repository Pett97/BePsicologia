<?php

namespace Tests\Unit\Models;

use App\Models\Insurance;
use Tests\TestCase;

class InsuranceTest extends TestCase
{
    private Insurance $insurance;

    public function setUp(): void
    {
        parent::setUp();
        $this->insurance = new Insurance([
            'name' => 'ConvenioTeste'
        ]);
        $this->insurance->save();
    }

    public function test_should_create_new_insurance(): void
    {
        $this->assertTrue($this->insurance->save());
        $this->assertCount(1, Insurance::all());
    }

    public function test_all_should_return_all_insurances(): void
    {
        $insurances[] = $this->insurance;
        $all = Insurance::all();
        $this->assertCount(1, $all);
        $this->assertEquals($insurances, $all);
    }
}
