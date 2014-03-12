<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Codeception;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Class CodeceptionTask
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class CodeceptionTask extends BaseTask
{

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array(
            $this->createBuildCommand($commandFactory),
            $this->createRunCommand($commandFactory),
            $this->createStoreResultCommand($commandFactory, $build->getBuildDir($this), $build->getProjectDir())
        );
    }

    /**
     * @param CommandFactory $commandFactory
     * @return bool
     */
    protected function createBuildCommand(CommandFactory $commandFactory)
    {
        $command = $this->createCommand($commandFactory);
        $command->addArg('build');

        return $command;
    }

    /**
     * @param CommandFactory $commandFactory
     * @return bool
     */
    protected function createRunCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();

        $specificSuite = null;
        $specificTest  = null;

        $command = $this->createCommand($commandFactory);
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

        return $command;
    }

    /**
     * @param CommandFactory $commandFactory
     * @param string         $buildDir
     * @param string         $projectDir
     * @return bool
     */
    protected function createStoreResultCommand(CommandFactory $commandFactory, $buildDir, $projectDir)
    {
        $options = $this->getOptions();

        $sourceDir      = $projectDir . '/' . $options['log_dir'];
        $destinationDir = $buildDir;

        $command = $commandFactory->createStoreResultCommand();
        $command->setResultDirs($sourceDir, $destinationDir);

        return $command;
    }

    /**
     * @param CommandFactory $commandFactory
     * @return Command
     */
    protected function createCommand(CommandFactory $commandFactory)
    {
        $options = $this->getOptions();
        $command = $commandFactory->createCommand();

        $command->addArg($options['bin']);
        $command->addArg('--no-ansi');
        $command->addArg('--no-interaction');

        if (!empty($options['config'])) {
            $command->addArg('--config=' . $options['config']);
        }

        if (!empty($options['verbose'])) {
            $command->addArg('-' . str_repeat('v', $options['verbose']));
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
            'cwd'         => '',
            'env'         => array(),
            'bin'         => '/usr/bin/env codecept',
            'config'      => '',
            'log_dir'     => 'tests/_log',
            'verbose'     => 0,
            'coverage'    => false,
            'suite'       => '',
            'test'        => '',
            'groups'      => array(),
            'envs'        => array(),
            'skip_suites' => array(),
            'skip_groups' => array()
        );
    }
}
