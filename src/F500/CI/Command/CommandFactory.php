<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

use F500\CI\Process\ProcessFactory;

/**
 * Class CommandFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
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
