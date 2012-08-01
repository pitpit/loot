<?php

namespace Pitpit\Silex\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Doctrine\DBAL\DriverManager;

/**
 * Command to execute the SQL needed to generate the database schema for
 * a given entity manager.
 *
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class CreateDatabaseDoctrineCommand extends Command
{
        /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('doctrine:database:create')
            ->setDescription('Creates the configured database')
            ->setHelp(<<<EOT
The <info>doctrine:database:create</info> command creates the default
connections database:

<info>php app/console doctrine:database:create</info>

You can also optionally specify the name of a connection to create the
database for:

<info>php app/console doctrine:database:create --connection=default</info>
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getApp();
        $connection = $app['db'];

        $params = $connection->getParams();
        $name = isset($params['path']) ? $params['path'] : $params['dbname'];

        unset($params['dbname']);

        $tmpConnection = DriverManager::getConnection($params);

        // Only quote if we don't have a path
        if (!isset($params['path'])) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        try {
            $tmpConnection->getSchemaManager()->createDatabase($name);
            $output->writeln(sprintf('<info>Created database for connection named <comment>%s</comment></info>', $name));
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not create database for connection named <comment>%s</comment></error>', $name));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $tmpConnection->close();
    }
}
