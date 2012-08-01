<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pitpit\Silex\Console\Command;

use Symfony\Component\Console\Application;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

/**
 * Provides some helper and convenience methods to configure doctrine commands in the context of bundles
 * and multiple connections/entity managers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class DoctrineCommandHelper
{
    /**
     * Convenience method to push the helper sets of a given entity manager into the application.
     *
     * @param Application $application
     */
    static public function setApplicationEntityManager(Application $application)
    {
        $app = $application->getApp();
        $em = $app['em'];
        $helperSet = $application->getHelperSet();
        $helperSet->set(new ConnectionHelper($em->getConnection()), 'db');
        $helperSet->set(new EntityManagerHelper($em), 'em');
    }

    static public function setApplicationConnection(Application $application, $connName)
    {
        $app = $application->getApp();
        $connection = $app['db'];
        $helperSet = $application->getHelperSet();
        $helperSet->set(new ConnectionHelper($connection), 'db');
    }
}
