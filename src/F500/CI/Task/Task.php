<?php

namespace F500\CI\Task;

use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use F500\CI\Wrapper\Wrapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface Task
{

    /**
     * @param string         $cn
     * @param CommandFactory $commandFactory
     * @param ProcessFactory $processFactory
     */
    public function __construct($cn, CommandFactory $commandFactory, ProcessFactory $processFactory);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return Wrapper[]
     */
    public function getWrappers();

    /**
     * @param string  $cn
     * @param Wrapper $wrapper
     */
    public function addWrapper($cn, Wrapper $wrapper);

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger);
}
