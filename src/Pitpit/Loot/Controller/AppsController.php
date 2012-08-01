<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;

class AppsController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        $controllers->get('/', function () use ($app) {

            return $app['twig']->render('Apps/home.html.twig', array());
        });
    }
}