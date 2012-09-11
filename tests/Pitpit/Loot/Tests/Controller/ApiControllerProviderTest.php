<?php

namespace Pitpit\Loot\Tests\Controller;

use Digex\WebTestCase;

class ApiControllerProviderTest extends WebTestCase
{
    public function testGetApiInfo()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('version', $data);
        $this->assertEquals('loot', $data['name']);
    }
}