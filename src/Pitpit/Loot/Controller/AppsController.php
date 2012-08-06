<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pitpit\Loot\Entity;

class AppsController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        $controllers->get('/{locale}/apps', function($locale) use ($app) {

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //@todo chech locale

            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId, 1);

            if (count($apps) > 0) {
                return $app->redirect($app['url_generator']->generate('apps_show', array(
                    'id' => $apps[0]->getId(),
                    'locale' => $locale,
                )));
            }

            return $app['twig']->render('Apps/home.html.twig', array(
                'user' => $user,    //@todo load user and pass it to the template in a global function
                'locale' => $locale, //@todo pass locale to the template in a global function
            ));

        })->bind('apps_home');

        $controllers->get('/{locale}/apps/{id}', function($locale, $id) use ($app) {

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //@todo chech locale

            //get all apps of the current user
            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($userId);

            //look for the current app
            $current = null;
            foreach ($apps as $myApp) {
                if ($myApp->getId() == $id) {
                    $current = $myApp;
                    break;
                }
            }

            if (null === $current) {
                throw new NotFoundHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $userId));
            }

            return $app['twig']->render('Apps/show.html.twig', array(
                'user' => $user, //@todo load user and pass it to the template in a global function
                'locale' => $locale, //@todo pass locale to the template in a global function
                'apps' => $apps,
                'current' => $current
            ));

        })->assert('id', '\d+')
          ->bind('apps_show');

        $controllers->get('/{locale}/apps/_new', function($locale) use ($app) {

            //load current user from session & database
            $userId = 1;
            $user = $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
            if (!$user || !$user->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            $myApp = new Entity\App();

            //@todo chech locale
            $form = $app['form.factory']->createBuilder('form', $myApp)
                ->add('name')
                ->add('description')
                ->getForm();

            return $app['twig']->render('Apps/form.html.twig', array(
                'user' => $user,    //@todo load user and pass it to the template in a global function
                'locale' => $locale, //@todo pass locale to the template in a global function
                'form' => $form->createView(),
            ));

        })->bind('apps_new');
    }
}