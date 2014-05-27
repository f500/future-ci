<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\AnsiblePlaybook;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Class AnsiblePlaybookTask
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\AnsiblePlaybook
 */
class AnsiblePlaybookTask extends BaseTask
{

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array(
            $this->createCommand($commandFactory)
        );
    }

    /**
     * @param CommandFactory $commandFactory
     * @return Command
     * @throws \RuntimeException
     */
    protected function createCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();

        if (empty($options['playbook'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no "playbook" configured.', $this->getCn()));
        }

        $command = $commandFactory->createCommand();

        $command->addArg($options['bin']);

        if (!empty($options['inventory'])) {
            $command->addArg('--inventory-file=' . escapeshellarg($options['inventory']));
        }

        if (!empty($options['limit'])) {
            $command->addArg('--limit=' . escapeshellarg($options['limit']));
        }

        if (!empty($options['extra_vars'])) {
            $extraVars = '';
            foreach ($options['extra_vars'] as $key => $value) {
                $extraVars .= sprintf(' %s="%s"', $key, $value);
            }
            $command->addArg('--extra-vars=' . escapeshellarg(ltrim($extraVars, ' ')));
        }

        $command->addArg(escapeshellarg($options['playbook']));

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
            'cwd'        => '',
            'env'        => array(),
            'bin'        => '/usr/bin/env ansible-playbook',
            'playbook'   => '',
            'inventory'  => null,
            'limit'      => null,
            'extra_vars' => array()
        );
    }
}
