<?php

namespace F500\CI\Suite;

use F500\CI\Task\Task;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface Suite
{

    /**
     * @param string $cn
     */
    public function __construct($cn);

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
     * @return Task[]
     */
    public function getTasks();

    /**
     * @param string $cn
     * @param Task   $task
     * @throws \InvalidArgumentException
     */
    public function addTask($cn, Task $task);

    /**
     * @param string $cn
     * @throws \InvalidArgumentException
     */
    public function removeTask($cn);

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return mixed
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger);
}
