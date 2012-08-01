<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;

abstract class Controller
{
    public static final function mount(Application $app, $mount = null)
    {
        $controllers = $app['controllers_factory'];
        $class = get_called_class();
        $class::build($app, $controllers);
        $app->mount(($mount===null)?'/':$mount, $controllers);
    }

    /**
     * Build the controllers
     *
     * @param $controllers Silex\ControllerCollection
     */
    protected static function build(Application $app, ControllerCollection $controllers) {}
}