<?php

namespace Loot\Api\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Loot\Api\MongoResource;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class ResourceServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['resource'] = $app->share(function(Application $app) {

            return new MongoResource();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
