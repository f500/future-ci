<?php

namespace F500\CI\Task;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DummyTask extends BaseTask
{
    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->startRun($dispatcher, $logger);
        $logger->log(LogLevel::INFO, 'Doing nothing (dummy task).');
        $this->finishRun($dispatcher, $logger);

        return true;
    }
}
