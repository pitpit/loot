<?php

namespace Digitas\Admin\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

/**
 * @author Damien Pitard <dpitard at digitas dot fr>
 * @copyright Digitas France
 */
class DefaultControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        /* home */
        $controllers->get('', function() use ($app) {

            return $app['twig']->render('Admin/Default/home.html.twig');

        })->bind('admin_home');

        return $controllers;
    }
}
