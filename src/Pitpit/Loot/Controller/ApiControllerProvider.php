<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        /**
         * Get info about the api
         */
        $controllers->get('/', function () use($app) {

            return $app->json(array('name' => 'loot', 'version' => $app['config']['app']['api_version']));
        });

        /**
         * Get info about the app $appId
         */
        $controllers->get('/{appId}', function ($appId) use($app) {

            return $app->json(array('name' => 'loot', 'version' => $app['config']['app']['api_version']));
        });

        return $controllers;
    }
}