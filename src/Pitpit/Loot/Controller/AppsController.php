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
        $controllers->get('/', function() use ($app) {

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId, 1);

            if (count($apps) > 0) {
                return $app->redirect($app['url_generator']->generate('apps_show', array('id' => $apps[0]->getId())));
            }

            return $app['twig']->render('Apps/home.html.twig', array(
                'user' => $user
            ));

        })->bind('apps_home');

        $controllers->get('/{id}', function($id) use ($app) {

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //get all apps of the current user
            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId);

            $current = null;
            if (null !== $id) {
                //look for the current app
                foreach ($apps as $application) {
                    if ($application->getId() == $id) {
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

            //@todo check the role the user have

            //@todo load user and pass it to the template in a global function

            return $app['twig']->render('Apps/show.html.twig', array(
                'user' => $user,
                'apps' => $apps,
                'current' => $current
            ));

        })->assert('id', '\d+')
          ->bind('apps_show');
    }
}