<?php

namespace Pitpit\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Cache\ArrayCache;
use Pitpit\Silex\Console\Command;

/**
 * @see https://github.com/flintstones/DoctrineOrm
 *
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @author Vyacheslav Slinko
 */
class DoctrineORMServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (!isset($app['db'])) {
            throw new \Exception('DoctrineServiceProvider is not registered');
        }

        $app['em'] = $app->share(function () use ($app) {
            return EntityManager::create($app['db'], $app['em.config'], $app['db.event_manager']);
        });

        $app['em.config'] = $app->share(function () use ($app) {
            $config = new Configuration();

            $config->setMetadataCacheImpl($app['em.cache']);
            $config->setQueryCacheImpl($app['em.cache']);

            if (isset($app['em.options']['proxy_dir'])) {
                $config->setProxyDir($app['em.options']['proxy_dir']);
                $config->setAutoGenerateProxyClasses(true);
            }

            if (isset($app['em.options']['proxy_namespace'])) {
                $config->setProxyNamespace($app['em.options']['proxy_namespace']);
            }

            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());

            if (isset($app['em.options']['entities'])) {
                $paths = $app['em.options']['entities'];
            } else {
                $paths = array();
            }

            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($paths));

            return $config;
        });

        //@todo user another cache type
        $app['em.cache'] = $app->share(function () {
            return new ArrayCache();
        });

        if (isset($app['em.class_path'])) {
            $app['autoloader']->registerNamespace('Doctrine\\ORM', $app['em.class_path']);
        }

        if (isset($app['console'])) {
            $app['console']->add(new Command\CreateSchemaDoctrineCommand());
            $app['console']->add(new Command\UpdateSchemaDoctrineCommand());

            //@todo should be in a DBAL related provider
            $app['console']->add(new Command\DropDatabaseDoctrineCommand());
            $app['console']->add(new Command\CreateDatabaseDoctrineCommand());

            //if doctrine/fixtures is enabled
            if (class_exists('\Doctrine\Common\DataFixtures\AbstractFixture')) {
                $app['console']->add(new Command\LoadDataFixturesDoctrineCommand());
            }
        }
    }

    public function boot(Application $app)
    {
    }
}