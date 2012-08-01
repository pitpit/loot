<?php

namespace Pitpit\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Pitpit\Silex\Console\Console;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['console'] = $app->share(function () use ($app) {

            return new Console($app);
        });
    }

    public function boot(Application $app)
    {
    }
}