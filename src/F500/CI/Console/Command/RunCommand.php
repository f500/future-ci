<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use F500\CI\Console\Helper\RunHelper;
use F500\CI\Event\Subscriber\ConsoleOutputSubscriber;
use F500\CI\Event\Subscriber\SlackSubscriber;
use F500\CI\Event\Subscriber\TimerSubscriber;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class RunCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Runs a CI build.')
            ->addArgument(
                'suite',
                InputArgument::REQUIRED,
                'Configuration file (or full path) of the suite to run.'
            )
            ->addArgument(
                'params',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Extra parameters for the suite. (key:value)'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $dispatcher = $this->getService('dispatcher');
        $phlack     = $this->getService('phlack');

        $dispatcher->addSubscriber(new SlackSubscriber($phlack));
        $dispatcher->addSubscriber(new ConsoleOutputSubscriber($output));
        $dispatcher->addSubscriber(new TimerSubscriber());
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new RunHelper($this->getContainer());
        $helper->execute($input, $output);
    }
}
