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
        $response = $this->post(route('domains.store'), ['domain' => 'https://www.example.com']);
        $response->assertResponseStatus(302);
        $this->seeInDatabase('domains', ['name' => 'https://www.example.com']);
        $response = $this->get(route('domains.show', ['id' => 1]));
        $response->assertResponseStatus(200);
    }

    public function testShowDomains()
    {
        $response = $this->get(route('domains.index'));
        $response->assertResponseStatus(200);
    }
}
