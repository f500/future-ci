<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use F500\CI\Build\Result;
use F500\CI\Event\Subscriber\ConsoleOutputSubscriber;
use F500\CI\Event\Subscriber\TimerSubscriber;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setName('ci:run')
            ->setDescription('Perform a CI run')
            ->addArgument('suite', InputArgument::REQUIRED, 'Common name of the suite to run.')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Format of configuration file.', 'yml');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $dispatcher = $this->getService('dispatcher');

        $dispatcher->addSubscriber(new ConsoleOutputSubscriber($output));
        $dispatcher->addSubscriber(new TimerSubscriber());
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \F500\CI\Runner\Configurator $configurator
         * @var \F500\CI\Runner\BuildRunner  $buildRunner
         */
        $configurator = $this->getService('f500ci.configurator');
        $runner       = $this->getService('f500ci.build_runner');

        $config = $configurator->loadConfig($input->getArgument('suite'), $input->getOption('format'));

        $suite = $configurator->createSuite($config['suite_class'], $config['suite_cn'], $config);
        $build = $configurator->createBuild($config['build_class'], $suite);

        $result = new Result($this->getService('filesystem'), $build->getBuildDir());

        if (!$runner->initialize($build)) {
            $output->writeln("<bg=red>\xE2\x9C\x98 Initializing build failed!</bg=red>");

            return;
        }

        if (!$runner->run($build, $result)) {
            return;
        }

        if (!$runner->cleanup($build)) {
            $output->writeln("<fg=magenta>\xE2\x9C\x98 Cleaning up build failed!</fg=magenta>");

            return;
        }
    }
}
