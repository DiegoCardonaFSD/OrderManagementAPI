<?php

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_homepage_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
