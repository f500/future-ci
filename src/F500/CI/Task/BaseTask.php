<?php

namespace F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Event\Events;
use F500\CI\Event\TaskEvent;
use F500\CI\Process\ProcessFactory;
use F500\CI\Wrapper\Wrapper;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class BaseTask implements Task
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var Wrapper[]
     */
    protected $wrappers;

    /**
     * @var CommandFactory
     */
    protected $commandFactory;

    /**
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @param string         $cn
     * @param CommandFactory $commandFactory
     * @param ProcessFactory $processFactory
     */
    public function __construct($cn, CommandFactory $commandFactory, ProcessFactory $processFactory)
    {
        $this->cn       = $cn;
        $this->options  = array();
        $this->wrappers = array();

        $this->commandFactory = $commandFactory;
        $this->processFactory = $processFactory;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_replace_recursive(
            $this->getDefaultOptions(),
            $this->options
        );
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return Wrapper[]
     */
    public function getWrappers()
    {
        return $this->wrappers;
    }

    /**
     * @param string  $cn
     * @param Wrapper $wrapper
     * @throws \InvalidArgumentException
     */
    public function addWrapper($cn, Wrapper $wrapper)
    {
        if (isset($this->wrappers[$cn])) {
            throw new \InvalidArgumentException(sprintf('Wrapper "%s" already added.', $cn));
        }

        $this->wrappers[$cn] = $wrapper;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     */
    protected function startRun(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $logger->log(LogLevel::DEBUG, sprintf('Task "%s" started.', $this->getCn()));
        $dispatcher->dispatch(Events::TaskStarted, new TaskEvent($this));
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     */
    protected function finishRun(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $dispatcher->dispatch(Events::TaskFinished, new TaskEvent($this));
        $logger->log(LogLevel::DEBUG, sprintf('Task "%s" finished.', $this->getCn()));
    }

    /**
     * @param Command         $command
     * @param LoggerInterface $logger
     * @return bool
     */
    protected function execute(Command $command, LoggerInterface $logger)
    {
        foreach ($this->getWrappers() as $wrapper) {
            $command = $wrapper->wrap($command);
        }

        $logger->log(LogLevel::INFO, sprintf('[%s] Executing: %s', $command->getId(), $command->stringify(true)));

        $process = $this->processFactory->create($command->getArgs(), $command->getCwd(), $command->getEnv());

        $logger->log(LogLevel::DEBUG, sprintf('[%s] Raw command: %s', $command->getId(), $process->getCommandLine()));

        $process->run();

        if ($process->isSuccessful()) {
            $logger->log(LogLevel::INFO, sprintf('[%s] Succeeded: %s', $command->getId(), $command->stringify(true)));
        } else {
            $logger->log(
                LogLevel::ERROR,
                sprintf('[%s] Failed: %s', $command->getId(), $command->stringify(true)),
                array(
                    'rc' => $process->getExitCode(),
                    'out' => $this->formatOutput($process->getOutput()),
                    'err' => $this->formatOutput($process->getErrorOutput())
                )
            );
        }

        return $process->isSuccessful();
    }

    /**
     * @param string $errors
     * @return string|array
     */
    protected function formatOutput($errors)
    {
        $errors = preg_split('/[\n\r]/', $errors);
        $errors = array_map('trim', $errors);
        $errors = array_filter($errors, 'strlen');
        $errors = array_values($errors);

        if (count($errors) == 1) {
            return reset($errors);
        } else {
            return $errors;
        }
    }
}
