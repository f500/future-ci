<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

/**
 * Class CommandFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
class CommandFactory
{

    /**
     * @var string
     */
    protected $commandClass;

    /**
     * @var string
     */
    protected $storeResultCommandClass;

    /**
     * @param string $commandClass
     * @param string $storeResultCommandClass
     */
    public function __construct($commandClass, $storeResultCommandClass)
    {
        $this->commandClass            = $commandClass;
        $this->storeResultCommandClass = $storeResultCommandClass;
    }

    /**
     * @return Command
     * @throws \RuntimeException
     */
    public function createCommand()
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

    /**
     * @return StoreResultCommand
     * @throws \RuntimeException
     */
    public function createStoreResultCommand()
    {
        $class = $this->storeResultCommandClass;

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Class "%s" does not exist.', $class));
        }

        $command = new $class();

        if (!$command instanceof StoreResultCommand) {
            throw new \RuntimeException(sprintf(
                'Class "%s" should be an instance of F500\CI\Command\StoreResultCommand.',
                $class
            ));
        }

        return $command;
    }
}
