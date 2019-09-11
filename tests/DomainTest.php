<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;



class DomainTest extends TestCase
{
    use DatabaseMigrations;


    protected function setUp():void
    {
        parent::setUp();
        $path = 'tests/fixtures/simple.html';
        $body = file_get_contents($path);
        $mock = new MockHandler([
            new Response(200, ['Content-Length' => 9], $body)
        ]);
        $handler = HandlerStack::create($mock);
        $this->app->bind('GuzzleClient', function ($app) use ($handler) {
            return new Client(['handler' => $handler]);
        });
    }

    public function testHomePage()
    {
        $response = $this->get(route('home'));
        $response->assertResponseStatus(200);
    }

    public function testDomainsStore()
    {
        $domain = factory('App\Domain')->make();
        $this->post(route('domains.store'), ['domain' => $domain->name]);
        $this->assertResponseStatus(302);
        $this->seeInDatabase('domains', [
            'name' => $domain->name,
            'status' => 200,
            'content_length' => 9,
            'header' => 'Hi header',
            'content' => 'Another test in the wall'
        ]);
    }

    public function testDomainsShow()
    {
        $domain = factory('App\Domain')->make();
        $domain->save();
        $this->get(route('domains.show', ['id' => $domain->id]));
        $this->assertResponseStatus(200);
    }

    public function testDomainsIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertResponseStatus(200);
    }
}
