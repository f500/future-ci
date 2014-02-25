<?php

namespace F500\CI\Command;

use F500\CI\Process\ProcessFactory;

class CommandFactory
{

    /**
     * @var string
     */
    protected $commandClass;

    /**
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @param string         $commandClass
     * @param ProcessFactory $processFactory
     */
    public function __construct($commandClass, ProcessFactory $processFactory)
    {
        $this->commandClass   = $commandClass;
        $this->processFactory = $processFactory;
    }

    /**
     * @return Command
     * @throws \RuntimeException
     */
    public function create()
    {
        $class = $this->commandClass;

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Class "%s" does not exist.', $class));
        }

        $command = new $class($this->processFactory);

        if (!$command instanceof Command) {
            throw new \RuntimeException(sprintf(
                'Class "%s" should be an instance of F500\CI\Command\Command.',
                $class
            ));
        }

        return $command;
    }
}
