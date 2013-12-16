<?php

namespace Loot\Api\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class EntryPointControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function(Application $app) {

            return $app->json(array(
                'name' => 'loot-api',
                'version' => '1.0.x-dev'
            ));

        })->value('_format', 'json');

        return $controllers;
    }
}
