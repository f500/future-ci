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
            ->setName('run')
            ->setDescription('Runs a CI build.')
            ->addArgument('suite', InputArgument::REQUIRED, 'Common name of the suite to run.')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Format of configuration file.', 'yml')
            ->addOption(
                'param',
                'p',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Extra parameters for the suite. (-p key:value -p "foo:bar\:baz")'
            );
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
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var \F500\CI\Runner\Configurator $configurator
         * @var \F500\CI\Runner\BuildRunner  $buildRunner
         */
        $configurator = $this->getService('f500ci.configurator');
        $runner       = $this->getService('f500ci.build_runner');

        $filename = $input->getArgument('suite');
        $format   = $input->getOption('format');

        $params = array();
        foreach ($input->getOption('param') as $param) {
            $split = preg_split('/(?<!\\\\):/', $param);
            if (count($split) != 2) {
                throw new \RuntimeException(sprintf('Param "%s" has incorrect format.', $param));
            }
            $params[$split[0]] = $split[1];
        }

        $config = $configurator->loadConfig($filename, $format, $params);

        $suite = $configurator->createSuite($config['suite']['class'], $config['suite']['cn'], $config['suite']);
        $build = $configurator->createBuild($config['build']['class'], $suite);

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
