<?php

namespace Pitpit\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Cache\ArrayCache;
use Pitpit\Silex\Console\Command\SchemaCreateCommand;
use Pitpit\Silex\Console\Command\UpdateSchemaCommand;

/**
 *
 * @see http://codeutopia.net/blog/2011/02/19/using-spatial-data-in-doctrine-2/
 * @see https://github.com/jhartikainen/doctrine2-spatial
 *
 * @author Damien Pitard <damien.pitard@gmail.com>
 */
class DoctrineSpatialServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (!isset($app['db'])) {
            throw new \Exception('DoctrineServiceProvider is not registered');
        }

        \Doctrine\DBAL\Types\Type::addType('point', 'Wantlet\ORM\PointType');

        if (!isset($app['em.config'])) {
            throw new \Exception('DoctrineORMServiceProvider is not registered');
        }

        $app['em.config']->addCustomNumericFunction('DISTANCE', 'Wantlet\ORM\Distance');
        $app['em.config']->addCustomNumericFunction('POINT_STR', 'Wantlet\ORM\PointStr');
    }

    public function boot(Application $app)
    {
    }
}