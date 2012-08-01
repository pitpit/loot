<?php

namespace Pitpit\Silex\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand;

/**
 * Command to drop the database schema for a set of classes based on their mappings.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class DropSchemaDoctrineCommand extends DropCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('doctrine:schema:drop')
            ->setDescription('Executes (or dumps) the SQL needed to drop the current database schema')
            ->setHelp(<<<EOT
The <info>doctrine:schema:drop</info> command generates the SQL needed to
drop the database schema of the default entity manager:

<info>php app/console doctrine:schema:drop --dump-sql</info>

Alternatively, you can execute the generated queries:

<info>php app/console doctrine:schema:drop --force</info>
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        DoctrineCommandHelper::setApplicationEntityManager($this->getApplication());

        parent::execute($input, $output);
    }
}