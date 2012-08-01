<?php

namespace Pitpit\Loot\Controller;

use Silex\Application;
use Silex\ControllerCollection;

class ApiController extends Controller
{
    protected static function build(Application $app, ControllerCollection $controllers)
    {
        $controllers->get('/', function () {
            return 'Blog home page';
        });
    }
}