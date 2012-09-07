<?php

namespace Pitpit\PostGis\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @author Vyacheslav Slinko
 */
class PostGisServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        \Doctrine\DBAL\Types\Type::addType('point', 'Pitpit\PostGis\DBAL\Types\PointType');

        $app['db']->getDatabasePlatform()->registerDoctrineTypeMapping('geography', 'string');
    }

    public function boot(Application $app) {}
}
