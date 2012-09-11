<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        /**
         * Get info about the api
         */
        $controllers->get('/', function () use ($app) {
            $data = array(
                'name' => 'loot',
                'version' => $app['config']['app']['api_version']
            );

            return $app->json($data);
        })->value('_format', 'json');

        /**
         * Get info about the app $appId
         */
        $controllers->get('/app/{id}', function ($id) use ($app) {

            $current = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findOneById($id);

            //@todo security check, does the current user has access to this app ?

            if (null === $current) {
                throw new NotFoundHttpException(sprintf('Application "%s" does not exist', $id));
            }

            $data = array(
                'id' => $current->getId(),
                'name' => $current->getName(),
                'description' => $current->getDescription()
            );

            return $app->json($data);
        })->value('_format', 'json')
          ->assert('id', '\d+');;

        return $controllers;
    }
}