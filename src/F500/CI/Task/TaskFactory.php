<?php

namespace F500\CI\Task;

use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;

class TaskFactory
{

    /**
     * @var CommandFactory
     */
    protected $commandFactory;

    /**
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @param CommandFactory $commandFactory
     * @param ProcessFactory $processFactory
     */
    public function __construct(CommandFactory $commandFactory, ProcessFactory $processFactory)
    {
        $this->commandFactory = $commandFactory;
        $this->processFactory = $processFactory;
    }

    /**
     * @param string $class
     * @param string $cn
     * @return Task
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" for task "%s" does not exist.', $class, $cn));
        }

        $task = new $class($cn, $this->commandFactory, $this->processFactory);

        if (!$task instanceof Task) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" for task "%s" should implement F500\CI\Task\Task.',
                $class,
                $cn
            ));
        }

        return $task;
    }
}
