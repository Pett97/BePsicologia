<?php

namespace Tests\Browser\Problems;

use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_should_redirect_if_not_authenticated_to_index(): void
    {
        $page = file_get_contents('http://web/brands');
        $this->assertTrue(http_response_code(302));

        $location = $http_response_header[10];
        $this->assertEquals('Location: /login', $location);
    }
}
