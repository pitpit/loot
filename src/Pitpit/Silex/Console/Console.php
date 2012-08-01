<?php

namespace Pitpit\Silex\Console;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Silex\Application as SilexApplication;
use Digex\Console\Command\AppAwareCommandInterface;

/**
 * @author Damien Pitard <dpitard at digitas dot fr>
 * @copyright Digitas France
 */
class Console extends ConsoleApplication
{
    protected $app;

    /**
     * Constructor.
     *
     * @param Silex\Application $app
     */
    public function __construct(SilexApplication $app)
    {
        $this->app = $app;

        parent::__construct('Digex');

        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    /**
     * Gets the Silex Application associated with this Console.
     *
     * @return Silex\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * {@inheritdoc}
     */
    public function add(Command $command)
    {
        if ($command instanceof AppAwareCommandInterface) {
            $command->setApp($this->app);
        }
        parent::add($command);
    }
}