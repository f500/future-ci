<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\Task;
use Psr\Log\LoggerInterface;

/**
 * Class TaskRunner
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Runner
 */
class TaskRunner
{

    /**
     * @var CommandFactory
     */
    protected $commandFactory;

    /**
     * @var CommandExecutor
     */
    protected $commandExecutor;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param CommandFactory  $commandFactory
     * @param CommandExecutor $commandExecutor
     * @param LoggerInterface $logger
     */
    public function __construct(
        CommandFactory $commandFactory,
        CommandExecutor $commandExecutor,
        LoggerInterface $logger
    ) {
        $this->commandFactory  = $commandFactory;
        $this->commandExecutor = $commandExecutor;
        $this->logger          = $logger;
    }

    /**
     * @param Task   $task
     * @param Build  $build
     * @param Result $result
     * @return bool
     */
    public function run(Task $task, Build $build, Result $result)
    {
        $commands = $task->buildCommands($build, $this->commandFactory);

        foreach ($commands as $command) {
            foreach ($task->getWrappers() as $wrapper) {
                $command = $wrapper->wrap($command, $this->commandFactory);
            }

            $success = $this->commandExecutor->execute($command, $this->logger);

            $result->addCommandResult($task, $command);

            if (!$success) {
                $result->markTaskAsBorked($task);

                return false;
            }
        }

        foreach ($task->getResultParsers() as $parser) {
            $parser->parse($task, $result);
        }

        return true;
    }
}
