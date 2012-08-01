<?php

namespace Pitpit\Silex\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand;

/**
 * Command to execute the SQL needed to generate the database schema for
 * a given entity manager.
 *
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jonathan H. Wage <jonwage@gmail.com>
 */
class CreateSchemaDoctrineCommand extends CreateCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('doctrine:schema:create')
            ->setDescription('Executes (or dumps) the SQL needed to generate the database schema.')
            ->setHelp(<<<EOT
The <info>doctrine:schema:create</info> command executes the SQL needed to
generate the database schema for the default entity manager:

<info>./app/console doctrine:schema:create</info>

Finally, instead of executing the SQL, you can output the SQL:

<info>./app/console doctrine:schema:create --dump-sql</info>
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
