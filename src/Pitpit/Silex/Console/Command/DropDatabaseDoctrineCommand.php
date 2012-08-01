<?php

namespace Pitpit\Silex\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

/**
 * Command to execute the SQL needed to generate the database schema for
 * a given entity manager.
 *
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class DropDatabaseDoctrineCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('doctrine:database:drop')
            ->setDescription('Drops the configured database')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Set this parameter to execute this action')
            ->setHelp(<<<EOT
The <info>doctrine:database:drop</info> command drops the default connections
database:

<info>php app/console doctrine:database:drop</info>

The --force parameter has to be used to actually drop the database.

<error>Be careful: All data in a given database will be lost when executing
this command.</error>
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

        $name = isset($params['path']) ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);

        if (!$name) {
            throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
        }

        if ($input->getOption('force')) {
            // Only quote if we don't have a path
            if (!isset($params['path'])) {
                $name = $connection->getDatabasePlatform()->quoteSingleIdentifier($name);
            }

            try {
                $connection->getSchemaManager()->dropDatabase($name);
                $output->writeln(sprintf('<info>Dropped database for connection named <comment>%s</comment></info>', $name));
            } catch (\Exception $e) {
                $output->writeln(sprintf('<error>Could not drop database for connection named <comment>%s</comment></error>', $name));
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }
        } else {
            $output->writeln('<error>ATTENTION:</error> This operation should not be executed in a production environment.');
            $output->writeln('');
            $output->writeln(sprintf('<info>Would drop the database named <comment>%s</comment>.</info>', $name));
            $output->writeln('Please run the operation with --force to execute');
            $output->writeln('<error>All data will be lost!</error>');
        }
    }
}
