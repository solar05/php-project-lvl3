<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class DomainTest extends TestCase
{
    use DatabaseMigrations;

    public function testMainPage()
    {
        $response = $this->get(route('home'));
        $response->assertResponseStatus(200);
    }

    public function testDomainAdding()
    {
        $response = $this->post(route('domains'), ['domain' => 'https://www.example.com']);
        $response->assertResponseStatus(302);
        $this->seeInDatabase('domains', ['name' => 'https://www.example.com']);
    }

    public function testShowDomains()
    {
        $response = $this->get('domains');
        $response->assertResponseStatus(200);
    }
}
