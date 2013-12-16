<?php

namespace Loot\Api\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class MongoServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['mongo'] = $app->share(function(Application $app) {

            return new \MongoClient($app['mongodb']['server']);
        });

        $app['mongo.db'] = $app->share(function(Application $app) {

            return new \MongoDB($app['mongo'], $app['mongodb']['db']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
