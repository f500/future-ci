<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueueWorkerCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
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
            ->setDescription('Starts a worker for the job-queue.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app     = $this->getContainer();
        $rootDir = $app['root_dir'];

        /** @var \F500\CI\Command\Process\ProcessFactory $processFactory */
        $processFactory = $app['f500ci.process_factory'];

        while (true) {

            $job = $this->pheanstalk
                ->watch('poolz-app')
                ->reserve();

            $payload = json_decode($job->getData(), true);

            if (!$this->isPayloadValid($payload)) {
                $this->pheanstalk->bury($job);
            } else {
                $args = array('exec', 'app/console', 'run', $payload['suite']);

                if (!empty($payload['params'])) {
                    foreach ($payload['params'] as $key => $value) {
                        $args[] = "--param={$key}:{$value}";
                    }
                }

                var_dump($args);

                $process = $processFactory->createProcess($args, $rootDir);
                $process->run();

                var_dump($process->isSuccessful(), $process->getOutput(), $process->getErrorOutput());

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
