<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Run;

use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Toolkit
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Run
 */
class Toolkit
{

    /**
     * @var string
     */
    protected $buildsDir;

    /**
     * @var CommandFactory
     */
    protected $commandFactory;

    /**
     * @var CommandExecutor
     */
    protected $commandExecutor;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var
     */
    protected $buildLogHandler;

    /**
     * @param string                   $buildsDir
     * @param CommandFactory           $commandFactory
     * @param CommandExecutor          $commandExecutor
     * @param EventDispatcherInterface $dispatcher
     * @param Filesystem               $filesystem
     * @param Logger                   $logger
     */
    public function __construct(
        $buildsDir,
        CommandFactory $commandFactory,
        CommandExecutor $commandExecutor,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $this->buildsDir = realpath($buildsDir);

        $this->commandFactory  = $commandFactory;
        $this->commandExecutor = $commandExecutor;
        $this->dispatcher      = $dispatcher;
        $this->filesystem      = $filesystem;
        $this->logger          = $logger;
    }

    /**
     * @return string
     */
    public function getBuildsDir()
    {
        return $this->buildsDir;
    }

    /**
     * @return CommandFactory
     */
    public function getCommandFactory()
    {
        return $this->commandFactory;
    }

    /**
     * @return CommandExecutor
     */
    public function getCommandExecutor()
    {
        return $this->commandExecutor;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $logfile
     * @throws \RuntimeException
     */
    public function activateBuildLogHandler($logfile)
    {
        if ($this->buildLogHandler) {
            throw new \RuntimeException('Build log already activated.');
        }

        $this->buildLogHandler = new StreamHandler($logfile);
        $this->getLogger()->pushHandler($this->buildLogHandler);
    }

    /**
     * @throws \RuntimeException
     */
    public function deactivateBuildLogHandler()
    {
        if (!$this->buildLogHandler) {
            throw new \RuntimeException('Build log not activated.');
        }

        $this->getLogger()->popHandler();
        $this->buildLogHandler = null;
    }
}
