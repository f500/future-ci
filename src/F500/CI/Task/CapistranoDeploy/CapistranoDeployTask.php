<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\CapistranoDeploy;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Class CapistranoDeployTask
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\CapistranoDeploy
 */
class CapistranoDeployTask extends BaseTask
{

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array(
            $this->createDeployCommand($commandFactory),
        );
    }

    /**
     * @param CommandFactory $commandFactory
     * @return bool
     * @throws \RuntimeException
     */
    protected function createDeployCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();

        if (empty($options['bin'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no "bin" configured.', $this->getCn()));
        }
        if (empty($options['stage'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no "stage" configured.', $this->getCn()));
        }
        if (empty($options['commit'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no "commit" configured.', $this->getCn()));
        }

        $command = $commandFactory->createCommand();

        $command->addArg($options['bin']);
        $command->addArg('exec');
        $command->addArg('cap');
        $command->addArg($options['stage']);
        $command->addArg('deploy');
        $command->addArg('commit=' . $options['commit']);

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
            'cwd'    => '',
            'env'    => array(),
            'bin'    => '/usr/bin/env bundle',
            'commit' => ''
        );
    }
}
