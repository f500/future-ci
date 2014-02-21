<?php

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('suite') . '.' . $input->getOption('format');

        $runner = $this->getService('f500ci.runner');

        $build = $runner->setup($filename);

        $runner->initialize($build);
        $runner->run($build);
        $runner->cleanup($build);
    }
}
