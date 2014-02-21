<?php

namespace F500\CI\Command;

class CommandFactory
{

    /**
     * @var string
     */
    protected $commandClass;

    /**
     * @param string $commandClass
     */
    public function __construct($commandClass)
    {
        $this->commandClass = $commandClass;
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

        $command = new $class();

        if (!$command instanceof Command) {
            throw new \RuntimeException(sprintf(
                'Class "%s" should be an instance of F500\CI\Command\Command.',
                $class
            ));
        }

        return $command;
    }
}
