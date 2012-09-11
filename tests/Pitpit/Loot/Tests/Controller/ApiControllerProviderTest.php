<?php

namespace Pitpit\Loot\Tests\Controller;

use Digex\WebTestCase;

class ApiControllerProviderTest extends WebTestCase
{
    public function testGetApiInfo()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/');

        $this->assertTrue($client->getResponse()->isOk());
    }
}