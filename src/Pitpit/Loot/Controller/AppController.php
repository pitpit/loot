<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AppController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        $controllers->get('/', function () use ($app) {

            //load current user from session & database
            $userId = 2;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User with id "%s" is not allowed to access this area', $userId));
            }

            //get all apps of the current user
            //$app = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUser($userId);
            $app = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId);

            return $app['twig']->render('Apps/home.html.twig', array('user' => $user));
        })->bind('app_home');
    }
}