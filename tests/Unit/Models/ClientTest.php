<?php

namespace Tests\Unit\Models;

use App\Models\Client;
use Lib\Paginator;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function test_can_set_name(): void
    {
        $client = new Client(
            name:"ana",
            phone:"429888534488",
            insurance_id:0,
            streetName:"rua teste",
            numberHouse:200,
            city_id:0
        );

        $this->assertEquals("ana", $client->getName());
    }

    public function test_dont_create_without_name(): void
    {
        $client = new Client(name:'');

        $hasErrors = $client->hasErrors();
        $this->assertFalse($hasErrors);
    }
}
