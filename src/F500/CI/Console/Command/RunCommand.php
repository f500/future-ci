<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use F500\CI\Event\Subscriber\ConsoleOutputSubscriber;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
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
        $this->getService('dispatcher')->addSubscriber(new ConsoleOutputSubscriber($output));
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('suite') . '.' . $input->getOption('format');

        $runner = $this->getService('f500ci.runner');

        $build = $runner->setup($filename);

        $output->writeln(
            sprintf(
                'Running build <fg=green>%s</fg=green> (<fg=green>%s</fg=green>):',
                $build->getDate()->format('Y-m-d H:i:s'),
                $build->getSuite()->getCn()
            )
        );

        $runner->initialize($build);
        $runner->run($build);
        $runner->cleanup($build);
    }
}
