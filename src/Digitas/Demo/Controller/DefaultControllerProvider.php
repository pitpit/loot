<?php

namespace Digitas\Demo\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Digitas\Demo\Entity\User;
use Digitas\Demo\Form\UserType;

/**
 * @author Damien Pitard <dpitard at digitas dot fr>
 * @copyright Digitas France
 */
class DefaultControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        //dispatch
        $controllers->get('/', function() use ($app) {

            return $app->redirect($app['url_generator']->generate('home', array('_locale' => $app['locale_fallback'])));
        });

        /* homepage */
        $controllers->get('/{_locale}', function($_locale) use ($app) {

            return $app['twig']->render('Demo/Default/home.html.twig');
        })->bind('home');

        /* create a new user*/
        $controllers->match('/{_locale}/user/_new', function($_locale) use ($app) {

            $user = new User();
            $form = $app['form.factory']->create(new UserType(), $user);

            if ($app['request']->getMethod() === 'POST') {
                $form->handleRequest($app['request'], array('method' => 'POST'));
                if ($form->isValid()) {
                    $app['em']->persist($user);
                    $app['em']->flush();

                    return $app->redirect($app['url_generator']->generate('user'));
                }
            }

            return $app['twig']->render('Demo/Default/new.html.twig', array(
                'form' => $form->createView()
            ));
        })->bind('new_user');

        //see a user
        $controllers->get('/{_locale}/user/{id}', function($_locale, $id) use ($app) {

            $users = $app['em']->getRepository('Digitas\Demo\Entity\User')->findAll();

            $current = null;
            if (null !== $id) {
                $current = $app['em']->getRepository('Digitas\Demo\Entity\User')->findOneById($id);

                if (null === $current) {
                    throw new NotFoundHttpException(sprintf('Unable to find user with id %s', $id));
                }
            }

            return $app['twig']->render('Demo/Default/user.html.twig', array(
                'users' => $users,
                'current_user' => $current
            ));
        })->bind('user')
          ->value('id', null);


        return $controllers;
    }
}
