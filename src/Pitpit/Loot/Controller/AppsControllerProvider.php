<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pitpit\Loot\Entity;
use Pitpit\Loot\Form\Type\AppNameType;
use Pitpit\Loot\Form\Type\AppEditType;

class AppsControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        
        //load current user from session & database
        //@todo move it in a global place
        $app['user'] = $app->share(function () use ($app) {
            $userId = 5;
            return $app['em']->getRepository('Pitpit\Loot\Entity\User')->findOneById($userId);
        });

        /**
         * The apps homepage
         *   - if the user has no apps, we provide a standard page
         *   - else the user is redirected to its first app
         */
        $controllers->get('/{locale}/apps', function($locale) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException('User is not allowed to access this area');
            }

            $apps = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findByUserId($app['user']->getId(), 1);

            if (count($apps) > 0) {
                return $app->redirect($app['url_generator']->generate('app_show', array(
                    'id' => $apps[0]->getId(),
                    'locale' => $locale,
                )));
            }

            //this is a form to create a new app (hidden in a modal)
            $form = $app['form.factory']->create(new AppNameType(), new Entity\App());

            return $app['twig']->render('Apps/home.html.twig', array(
                'form' => $form->createView()
            ));

        })
        ->bind('apps');

        /**
         * Show the app $id
         */
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
                throw new AccessDeniedHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $app['user']->getId()));
            }

            //this is a form to create a new app (hidden in a modal)
            $form = $app['form.factory']->create(new AppNameType(), new Entity\App());

            return $app['twig']->render('Apps/show.html.twig', array(
                'apps' => $apps,
                'current' => $current,
                'form' => $form->createView()
            ));

        })
        ->assert('id', '\d+')
        ->bind('app_show');

        /**
         * show the form to edit ap $id and store its update on POST
         */
        $controllers->match('/{locale}/app/{id}/_edit', function($locale, $id) use ($app) {

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
                throw new AccessDeniedHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $app['user']->getId()));
            }

            $form = $app['form.factory']->create(new AppEditType(), $current);


            if ($app['request']->getMethod() == 'POST') {

                $form->bindRequest($app['request']);
                if ($form->isValid()) {

                    $app['em']->persist($current);
                    $app['em']->flush();

                    return $app->redirect($app['url_generator']->generate('app_show', array(
                        'id' => $current->getId(),
                        'locale' => $locale,
                    )));
                }
            }

            return $app['twig']->render('Apps/edit.html.twig', array(
                'apps' => $apps,
                'current' => $current,
                'form' => $form->createView()
            ));

        })
        ->assert('id', '\d+')
        ->bind('app_edit');

        /**
         * Delete the app $id
         */
        $controllers->get('/{locale}/app/{id}/_delete', function($locale, $id) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User "%s" is not allowed to access this area', $userId));
            }

            //get all apps of the current user
            $current = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findOneByIdAndUserId($id, $app['user']->getId());

            if (!$current) {
                throw new AccessDeniedHttpException(sprintf('App "%s" does not exist or user "%s" is not allowed to access it', $id, $app['user']->getId()));
            }

            $app['em']->remove($current);
            $app['em']->flush();

            return $app->redirect($app['url_generator']->generate('apps', array(
                'locale' => $locale,
            )));
        })
        ->assert('id', '\d+')
        ->bind('app_delete');

        /**
         * @category ajax
         */
        $controllers->post('/{locale}/app/_create', function($locale) use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User is not allowed to access this area'));
            }

            //@todo check the max number of apps

            $current = new Entity\App();

            $form = $app['form.factory']->create(new AppNameType(), $current);

            $form->bindRequest($app['request']);

            if (!$form->isValid()) {
                throw new \Exception('Invalid form...', 400);
            }

            //check if an app with the same name exist
            $same = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findOneByNameAndUserId($name, $app['user']->getId());
            if ($same !== null) {
                throw new \Exception('An app with the same name already exists', 400);
            }

            $current->addUser($app['user'], Entity\UserApp::CREATOR_ROLE);

            $app['em']->persist($current);
            $app['em']->flush();

            return $app->redirect($app['url_generator']->generate('app_show', array(
                'id' => $current->getId(),
                'locale' => $locale,
            )));
        })
        ->bind('app_create');

        /**
         * Get app info
         *
         * @todo move it to a better place
         *
         * @category ajax
         * @category api
         */
        $controllers->get('/app/_query', function() use ($app) {

            if (!$app['user'] || !$app['user']->getIsDeveloper()) {
                throw new AccessDeniedHttpException(sprintf('User is not allowed to access this area'));
            }

            $myApp = null;
            if ($name = $app['request']->get('name')) {
                $myApp = $app['em']->getRepository('Pitpit\Loot\Entity\App')->findOneByNameAndUserId($name, $app['user']->getId());
            }

            if (!$myApp) {
                $data = array();
            } else {
                $data = array(
                    'id' => $myApp->getId(),
                    'name' => $myApp->getName(),
                );
            }

            return $app->json($data);
        })
        ->assert('_format', 'json')
        ->bind('api_app');

        return $controllers;
    }
}