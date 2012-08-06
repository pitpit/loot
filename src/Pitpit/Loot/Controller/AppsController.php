<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pitpit\Loot\Entity;
use Pitpit\Loot\Form;

class AppsController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        //load current user from session & database
        //@todo move it in a global place
        $app['user'] = $app->share(function () use ($app) {
            $userId = 2;
            return $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
        });

        /**
         * The apps homepage
         *   - if the user has no apps, we provide a standard page
         *   - else the user is redirected to its first app
         */
        $controllers->get('/{locale}/apps', function($locale) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($app['user']->getId(), 1);

            if (count($apps) > 0) {
                return $app->redirect($app['url_generator']->generate('app_show', array(
                    'id' => $apps[0]->getId(),
                    'locale' => $locale,
                )));
            }

            return $app['twig']->render('Apps/home.html.twig');

        })
        ->bind('apps');

        $controllers->get('/{locale}/app/{id}', function($locale, $id) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //get all apps of the current user
            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($app['user']->getId());

            //look for the current app
            //this is also a security check
            $current = null;
            foreach ($apps as $myApp) {
                if ($myApp->getId() == $id) {
                    $current = $myApp;
                    break;
                }
            }

            if (null === $current) {
                throw new NotFoundHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $app['user']->getId()));
            }

            return $app['twig']->render('Apps/show.html.twig', array(
                'apps' => $apps,
                'current' => $current
            ));

        })
        ->assert('id', '\d+')
        ->bind('app_show');

        /**
         * Get the form to create a new app
         *
         * @category ajax
         */
        $controllers->get('/{locale}/app/_new', function($locale) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User is not allowed to access this area'));
            }

            $myApp = new Entity\App();

            $form = $app['form.factory']->create(new Form\AppNameType(), $myApp);

            return $app['twig']->render('Apps/new.html.twig', array(
                'form' => $form->createView(),
            ));

        })
        ->bind('app_new');

        // /**
        //  * Get app info
        //  *
        //  * @todo move it to a better place
        //  *
        //  * @category ajax
        //  * @category api
        //  */
        // $controllers->get('/app.{_format}', function() use ($app) {

        //     if (!$app['user'] || !$app['user']->getIsDeveloper()) {
        //         throw new AccessDeniedHttpException(sprintf('User is not allowed to access this area'));
        //     }

        //     $myApp = null;
        //     if ($name = $app['request']->get('name')) {
        //         $myApp = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findOneByName($name);
        //     }

        //     if (!$myApp) {
        //         $data = array();
        //     } else {
        //         $data = array(
        //             'id' => $myApp->getId(),
        //             'name' => $myApp->getName(),
        //         );
        //     }

        //     return $app->json($data);
        // })
        // ->assert('_format', 'json')
        // ->bind('api_app');
    }
}