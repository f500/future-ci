<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueuePushCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class QueuePushCommand extends QueueCommand
{

    protected function configure()
    {
        $this
            ->setName('queue:push')
            ->setDescription('Push a job to the job-queue.');

        parent::configure();

        $this
            ->addArgument(
                'suite',
                InputArgument::REQUIRED,
                'Configuration file (or full path) of the suite to run.'
            )
            ->addArgument(
                'params',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Extra parameters for the suite. (key:value)'
            )
            ->addOption(
                'priority',
                null,
                InputOption::VALUE_OPTIONAL,
                'The priority for this job.',
                \Pheanstalk_PheanstalkInterface::DEFAULT_PRIORITY
            )
            ->addOption(
                'ttr',
                null,
                InputOption::VALUE_OPTIONAL,
                'The time to run for this job (in seconds).',
                900
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $payload = array(
            'suite'  => $input->getArgument('suite'),
            'params' => array()
        );

        foreach ($input->getArgument('params') as $param) {
            $split = preg_split('/(?<!\\\\):/', $param);
            if (count($split) != 2) {
                throw new \RuntimeException(sprintf('Param "%s" has incorrect format.', $param));
            }
            $payload['params'][$split[0]] = $split[1];
        }

        $this->pheanstalk
            ->useTube('poolz-app')
            ->put(
                json_encode($payload),
                $input->getOption('priority'),
                \Pheanstalk_PheanstalkInterface::DEFAULT_DELAY,
                $input->getOption('ttr')
            );
    }
}
