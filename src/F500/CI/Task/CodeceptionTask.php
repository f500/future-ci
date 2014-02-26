<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Run\Toolkit;

/**
 * Class CodeceptionTask
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class CodeceptionTask extends BaseTask
{

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit)
    {
        $this->startRun($toolkit);

        $result = true;
        if ($result && !$this->runBuild($toolkit)) {
            $result = false;
        }
        if ($result && !$this->runRun($toolkit)) {
            $result = false;
        }

        $this->finishRun($toolkit);

        return $result;
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    protected function runBuild(Toolkit $toolkit)
    {
        $command = $this->createCommand($toolkit->getCommandFactory());
        $command->addArg('build');

        $command = $this->wrapCommand($command);

        return $command->execute($toolkit->getLogger());
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    protected function runRun(Toolkit $toolkit)
    {
        $options = $this->getOptions();

        $specificSuite = null;
        $specificTest  = null;

        $command = $this->createCommand($toolkit->getCommandFactory());
        $command->addArg('run');
        $command->addArg('--json');
        $command->addArg('--silent');
        $command->addArg('--no-exit');

        if (!empty($options['coverage'])) {
            $command->addArg('--coverage');
        }

        if (!empty($options['suite'])) {
            $specificSuite = $options['suite'];
        } elseif (!empty($options['skip_suites'])) {
            foreach ($options['skip_suites'] as $suite) {
                $command->addArg('--skip=' . $suite);
            }
        }

        if (!empty($options['test'])) {
            $specificTest = $options['test'];
        }

        if (!empty($options['groups'])) {
            foreach ($options['groups'] as $group) {
                $command->addArg('--group=' . $group);
            }
        } elseif (!empty($options['skip_groups'])) {
            foreach ($options['skip_groups'] as $suite) {
                $command->addArg('--skip-group=' . $suite);
            }
        }

        if (!empty($options['envs'])) {
            foreach ($options['envs'] as $env) {
                $command->addArg('--env=' . $env);
            }
        }

        if ($specificSuite) {
            $command->addArg($specificSuite);
        }
        if ($specificTest) {
            $command->addArg($specificTest);
        }

        $command = $this->wrapCommand($command);

        return $command->execute($toolkit->getLogger());
    }

    /**
     * @param CommandFactory $commandFactory
     * @return Command
     */
    protected function createCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();
        $command = $commandFactory->create();

        $command->addArg($options['bin']);
        $command->addArg('--no-ansi');
        $command->addArg('--no-interaction');

        if ($options['config']) {
            $command->addArg('--config=' . $options['config']);
        }

        if ($options['verbose']) {
            $command->addArg('--' . str_repeat('v', $options['verbose']));
        }

        if ($options['cwd']) {
            $command->setCwd($options['cwd']);
        }

        foreach ($options['environment'] as $name => $value) {
            $command->addEnv($name, $value);
        }

        return $command;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            'cwd'         => null,
            'environment' => array(),
            'bin'         => '/usr/bin/env codecept',
            'config'      => null,
            'verbose'     => 0,
            'coverage'    => false,
            'suite'       => null,
            'test'        => null,
            'groups'      => array(),
            'envs'        => array(),
            'skip_suites' => array(),
            'skip-groups' => array()
        );
    }
}
