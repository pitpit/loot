<?php

namespace Pitpit\Loot\Tests\Controller;

use Digex\WebTestCase;
use Pitpit\Loot\Entity;

class ApiControllerProviderTest extends WebTestCase
{
    public function testGetApi()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);
        
        $this->assertEquals('loot', $data['name']);
        $this->assertArrayHasKey('version', $data);
    }

    public function testGetApp()
    {
        $client = $this->createClient();

        $app1 = new Entity\App();
        $app1->setName('test app');
        // $app1->addUser($user, Entity\UserApp::CREATOR_ROLE);
        $app1->setDescription('test description');
        $this->app['em']->persist($app1);
        $this->app['em']->flush();

        $id = $app1->getId();

        $crawler = $client->request('GET', '/api/app/' . $id);
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);

        $this->assertEquals('test app', $data['name']);
        $this->assertEquals('test description', $data['description']);
    }

    public function testGetAppUnknownId()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/api/app/99999');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isNotFound());
    }

    public function testGetAppNullId()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/api/app/');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isNotFound());
    }

    public function testGetAppNonNumericId()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/api/app/zzz');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isNotFound());
    }
}