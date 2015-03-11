<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Exec;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Task used to execute a shell command.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class ExecTask extends BaseTask
{

    /**
     * Initializes the commands for this task.
     *
     * @param Build          $build
     * @param CommandFactory $commandFactory
     *
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array($this->createCommand($commandFactory));
    }

    /**
     * Generates a command that can be performed on the target location using the provided options.
     *
     * This command will run the binary provided using the "bin" option together with a series of arguments
     * provided by the "args" option (array). You can control from where this is executed by providing an option "cwd"
     * that will first navigate to the given location before executing the provided binary.
     *
     * @param CommandFactory $commandFactory
     *
     * @throws \Exception if no "bin" option is provided; this is required.
     *
     * @return Command
     */
    protected function createCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();
        $command = $commandFactory->createCommand();

        if ( ! $options['bin']) {
            throw new \Exception('The "bin" option is required for the "Exec" task');
        }

        $command->addArg($options['bin']);
        foreach ($options['args'] as $arg) {
            $command->addArg($arg);
        }

        if ( ! empty($options['cwd'])) {
            $command->setCwd($options['cwd']);
        }

        if (! empty($options['env'])) {
            foreach ($options['env'] as $name => $value) {
                $command->addEnv($name, $value);
            }
        }

        return $command;
    }

    /**
     * Returns the defaults for all supported options.
     *
     * @return mixed[]
     */
    protected function getDefaultOptions()
    {
        return array(
            'cwd'         => '',      // Current working directory for this task.
            'env'         => array(), // environment in which to run.
            'bin'         => null,    // the full path to the binary that needs to be run; use /usr/bin/env if unsure
                                      // where it is
            'args'        => array(), // a series of options and arguments (should include and prefixing hyphens).
        );
    }
}
