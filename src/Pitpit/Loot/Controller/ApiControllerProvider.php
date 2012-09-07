<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class ApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        $controllers->get('/', function () {
            return 'Blog home page';
        });

        return $controllers;
    }
}