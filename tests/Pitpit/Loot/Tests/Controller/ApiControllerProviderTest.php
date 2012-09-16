<?php

namespace Pitpit\Loot\Tests\Controller;

use Digex\WebTestCase;
use Pitpit\Loot\Entity;

class ApiControllerProviderTest extends WebTestCase
{
    protected function getTestApp()
    {
        $app = new Entity\App();
        $app->setName('test app');
        $app->setDescription('test description');
        $this->app['em']->persist($app);
        $this->app['em']->flush();

        return $app;
    }

    protected function getTestUser()
    {
        $user = new Entity\User();
        $user->setEmail('test@test.fr');
        $this->app['em']->persist($user);
        $this->app['em']->flush();

        return $user;
    }

    /*
     * api
     */
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

    /*
     * api/app
     */
    public function testGetApp()
    {
        $app = $this->getTestApp();

        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/app/' . $app->getId());
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);

        $this->assertEquals($app->getId(), $data['id']);
        $this->assertEquals('test app', $data['name']);
        $this->assertEquals('test description', $data['description']);
    }

    public function testGetAppUnknownId()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/api/app/99999');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode(), 'Should return a 404 error');
    }

    public function testGetAppNullId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/app/');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode(), 'Should return a 404 error');
    }

    public function testGetAppNonNumericId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/app/zzz');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode(), 'Should return a 404 error');
    }

    /*
     * login
     */
    public function testLoginWithEmail()
    {
        $user = $this->getTestUser();

        $client = $this->createClient();
        $crawler = $client->request('POST', '/login', array(
            'email' => $user->getEmail(),
            'password' => 'apassword'
        ));
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertEquals(404, $response->getStatusCode(), 'Should return a 404 error');
    }

    /*
     * api/me
     */
    public function testGetMeNotLogged()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/me');
        $response = $client->getResponse();

        $this->assertEquals(403, $response->getStatusCode(), 'Should return a 403 error');
    }

    public function testGetMeUnknownLocation()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $user = $this->getTestUser();

        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/me');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);

        $this->assertEquals($user->getId(), $data['id']);
        $this->assertArrayHasKey('location', $data);
        $this->assertNull($data['location']);
    }

    public function testGetMe()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $user = $this->getTestUser();

        $client = $this->createClient();
        $crawler = $client->request('GET', '/api/me');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);

        $this->assertEquals($user->getId(), $data['id']);
        $this->assertArrayHasKey('location', $data);
        $this->assertArrayHasKey('location', $data);
    }

    public function testSetMeLocation()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $user = $this->getTestUser();

        $data = array(
            'location' => array(
                'type' => 'Point',
                'coordinates' => array(2.291816, 48.898173)
            )
        );
        $content = json_encode($data);

        $client = $this->createClient();
        $crawler = $client->request('PUT', '/api/me', array(), array(), array(), $content);

        $user = $this->app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($user->getId());
        $this->assertNotNull($user->getLocation());
        $this->assertEquals(2.291816, $user->getLocation()->getLatitude());
        $this->assertEquals(48.898173, $user->getLocation()->getLongitude());
    }

    public function testSetMeNotLogged()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testSetMeLocationWrongFormat()
    {
        $this->markTestIncomplete('Test that the json is in GeoJSON format. Not implemented yet.');
    }

    public function testGetClosestObjectsUnknownUserLocation()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $app = $this->getTestApp();
        $id = $app->getId();

        //@todo should throw a 40x http error
    }

    public function testGetClosestObjectsNoObjects()
    {
        $this->markTestIncomplete('Not implemented yet.');

        $client = $this->createClient();

        $app = $this->getTestApp();

        $latitude = '';
        $longitude = '';

        $crawler = $client->request('GET', '/api/app/' . $app->getId(). '/objects');
        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertTrue($response->isOk());
        $this->assertNotNull($data);

        $this->assertEquals($app->getId(), $data['id']);
        $this->assertEquals('0', $data['count']);
        $this->assertEquals(array(), $data['objects']);
    }
}