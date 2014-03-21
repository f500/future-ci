<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\VagrantUp;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Class VagrantUpTask
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class VagrantUpTask extends BaseTask
{

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array(
            $this->createUpCommand($commandFactory)
        );
    }

    /**
     * @param CommandFactory $commandFactory
     * @return Command
     */
    protected function createUpCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();

        $command = $commandFactory->createCommand();

        $command->addArg($options['bin']);
        $command->addArg('up');
        $command->addArg('--no-provision');

        if (!empty($options['vm_name'])) {
            $command->addArg($options['vm_name']);
        }

        if (!empty($options['cwd'])) {
            $command->setCwd($options['cwd']);
        }

        if (!empty($options['env'])) {
            foreach ($options['env'] as $name => $value) {
                $command->addEnv($name, $value);
            }
        }

        return $command;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            'cwd'     => '',
            'env'     => array(),
            'bin'     => '/usr/bin/env vagrant',
            'vm_name' => ''
        );
    }
}
