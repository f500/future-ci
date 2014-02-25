<?php

namespace F500\CI\Run;

use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

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
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param string                   $buildsDir
     * @param CommandFactory           $commandFactory
     * @param ProcessFactory           $processFactory
     * @param EventDispatcherInterface $dispatcher
     * @param Filesystem               $filesystem
     * @param LoggerInterface          $logger
     */
    public function __construct(
        $buildsDir,
        CommandFactory $commandFactory,
        ProcessFactory $processFactory,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->buildsDir = realpath($buildsDir);

        $this->commandFactory = $commandFactory;
        $this->processFactory = $processFactory;
        $this->dispatcher     = $dispatcher;
        $this->filesystem     = $filesystem;
        $this->logger         = $logger;
    }

    /**
     * @return CommandFactory
     */
    public function getCommandFactory()
    {
        return $this->commandFactory;
    }

    /**
     * @return ProcessFactory
     */
    public function getProcessFactory()
    {
        return $this->processFactory;
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
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
