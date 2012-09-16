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

        /**
         * The apps homepage
         *   - if the user has no apps, we provide a standard page
         *   - else the user is redirected to its first app
         */
        $controllers->get('/{locale}/login', function($locale) use ($app) {

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

        return $controllers;
    }
}