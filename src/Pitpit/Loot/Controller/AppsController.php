<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppsController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        $controllers->get('/{id}', function($id) use ($app) {

            $id = (integer)$id;

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //get all apps of the current user
            //$app = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUser($userId);
            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId);

            $current = null;
            if (null !== $id) {
                //look for the current app

                foreach ($apps as $application) {
                    if ($application->getId() === $id) {
                        $current = $application;
                        break;
                    }
                }

                if (null === $current) {
                    throw new NotFoundHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $userId));
                }
            } else if (count($apps) > 0) {
                $current = $apps[0];
            }

            return $app['twig']->render('Apps/home.html.twig', array(
                'user' => $user,
                'apps' => $apps,
                'current' => $current
            ));

        })->assert('id', '\d*')
          ->value('id', null)
          ->bind('apps');
    }
}