<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Pheanstalk\Job;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueueWorkerCommand
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class QueueWorkerCommand extends QueueCommand
{

    protected function configure()
    {
        $this
            ->setName('queue:worker')
            ->setDescription('Starts a worker for the job-queue.')
            ->addOption(
                'tube',
                null,
                InputOption::VALUE_REQUIRED,
                'The tube to push this job to.',
                'ci-builds'
            );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getContainer();
        $rootDir = $app['root_dir'];

        /** @var \F500\CI\Command\Process\ProcessFactory $processFactory */
        $processFactory = $app['f500ci.process_factory'];

        while (true) {
            /* @var Job $job */
            $job = $this->pheanstalk
                ->watch($input->getOption('tube'))
                ->reserve();

            $payload = json_decode($job->getData(), true);

            if (!$this->isPayloadValid($payload)) {
                $output->writeln(sprintf('Buried job <comment>%s</comment> because of an invalid payload.',
                    $job->getId()));
                $this->pheanstalk->bury($job);

            } else {
                $args = array('exec', 'app/console', '--ansi', 'run', $payload['suite']);

                if (!empty($payload['params'])) {
                    $args[] = "--build-info " . base64_encode(json_encode($payload['params']));
                }
                
                $process = $processFactory->createProcess($args, $rootDir, null, null, 1200);
                $process->run(
                    function ($type, $buffer) use ($output) {
                        $output->write($buffer, false, OutputInterface::OUTPUT_RAW);
                    }
                );

                $this->pheanstalk->delete($job);
            }
        }
    }

    /**
     * @param mixed $payload
     * @return bool
     */
    protected function isPayloadValid($payload)
    {
        if (!is_array($payload)) {
            return false;
        }

        if (empty($payload['suite']) || !is_string($payload['suite'])) {
            return false;
        }

        if (!empty($payload['params']) && !is_array($payload['params'])) {
            return false;
        }

        return true;
    }
}
