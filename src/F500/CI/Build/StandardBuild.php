<?php

namespace F500\CI\Build;

use F500\CI\Event\BuildEvent;
use F500\CI\Event\Events;
use F500\CI\Suite\Suite;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StandardBuild implements Build
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite)
    {
        $this->cn    = $cn;
        $this->suite = $suite;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function initialize(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        return true;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $logger->log(LogLevel::DEBUG, sprintf('Build "%s" started.', $this->getCn()));
        $dispatcher->dispatch(Events::BuildStarted, new BuildEvent($this));

        $result = $this->suite->run($dispatcher, $logger);

        $dispatcher->dispatch(Events::BuildFinished, new BuildEvent($this));
        $logger->log(LogLevel::DEBUG, sprintf('Build "%s" finished.', $this->getCn()));

        return $result;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function cleanup(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        return true;
    }
}
