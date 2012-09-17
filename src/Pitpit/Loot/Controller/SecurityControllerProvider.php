<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pitpit\Loot\Entity;
use Pitpit\Loot\Form\Type\AppNameType;
use Pitpit\Loot\Form\Type\AppEditType;

class SecurityControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('login', function() use ($app) {

            return $app['twig']->render('Security/login.html.twig', array(
                'error'         => $app['security.last_error']($app['request']),
                'last_username' => $app['session']->get('_security.last_username'),
            ));

        })
        ->bind('login');

        return $controllers;
    }
}