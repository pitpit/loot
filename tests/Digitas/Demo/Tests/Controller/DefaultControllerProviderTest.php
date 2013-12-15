<?php

namespace Digitas\Demo\Tests\Controller;

use Digex\WebTestCase;

class DefaultControllerProviderTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());
    }
}