<?php

namespace F500\CI\Build;

use F500\CI\Suite\Suite;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface Build
{
    /**
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function initialize(EventDispatcherInterface $dispatcher, LoggerInterface $logger);

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger);

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function cleanup(EventDispatcherInterface $dispatcher, LoggerInterface $logger);
}
