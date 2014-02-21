<?php

namespace F500\CI\Suite;

use F500\CI\Event\Events;
use F500\CI\Event\SuiteEvent;
use F500\CI\Task\Task;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StandardSuite implements Suite
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Task[]
     */
    protected $tasks;

    /**
     * @param string $cn
     */
    public function __construct($cn)
    {
        $this->cn    = $cn;
        $this->tasks = array();
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param string $cn
     * @param Task   $task
     * @throws \InvalidArgumentException
     */
    public function addTask($cn, Task $task)
    {
        if (isset($this->tasks[$cn])) {
            throw new \InvalidArgumentException(sprintf('Task "%s" already added.', $cn));
        }

        $this->tasks[$cn] = $task;
    }

    /**
     * @param string $cn
     * @throws \InvalidArgumentException
     */
    public function removeTask($cn)
    {
        if (!isset($this->tasks[$cn])) {
            throw new \InvalidArgumentException(sprintf('Task "%s" not added.', $cn));
        }

        unset($this->tasks[$cn]);
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return mixed
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $logger->log(LogLevel::DEBUG, sprintf('Suite "%s" started.', $this->getCn()));
        $dispatcher->dispatch(Events::SuiteStarted, new SuiteEvent($this));

        $result = true;
        foreach ($this->getTasks() as $task) {
            if (!$task->run($dispatcher, $logger)) {
                $result = false;
                break;
            }
        }

        $dispatcher->dispatch(Events::SuiteFinished, new SuiteEvent($this));
        $logger->log(LogLevel::DEBUG, sprintf('Suite "%s" finished.', $this->getCn()));

        return $result;
    }
}
