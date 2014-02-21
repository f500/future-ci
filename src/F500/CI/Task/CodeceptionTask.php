<?php

namespace F500\CI\Task;

use F500\CI\Command\Command;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CodeceptionTask extends BaseTask
{

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     * @return bool
     */
    public function run(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->startRun($dispatcher, $logger);

        $result = true;
        if ($result && !$this->execute($this->getCommand('build'), $logger)) {
            $result = false;
        }
        if ($result && !$this->execute($this->getCommand('run'), $logger)) {
            $result = false;
        }

        $this->finishRun($dispatcher, $logger);

        return $result;
    }

    /**
     * @param $action
     * @return Command
     */
    protected function getCommand($action)
    {
        $options = $this->getOptions();
        $command = $this->commandFactory->create();

        $command->addArg($options['bin']);
        $command->addArg('--no-ansi');
        $command->addArg('--no-interaction');

        if ($options['verbose']) {
            $command->addArg('--' . str_repeat('v', $options['verbose']));
        }

        $command->addArg($action);

        if ($options['config']) {
            $command->addArg('--config=' . $options['config']);
        }

        if ($action == 'run') {
            foreach ($options['env'] as $env) {
                $command->addArg('--env=' . $env);
            }
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
            'env'         => array()
        );
    }
}
