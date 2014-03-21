<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Event\BuildEvent;
use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\Events;
use F500\CI\Event\TaskRunEvent;
use F500\CI\Filesystem\Filesystem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PhpSpec\Exception\Exception as PhpSpecException;
use Prophecy\Exception\Exception as ProphecyException;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class BuildRunner
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Runner
 */
class BuildRunner
{

    /**
     * @var TaskRunner
     */
    protected $taskRunner;

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
     * @var \Monolog\Handler\HandlerInterface
     */
    protected $buildLogHandler;

    /**
     * @param  TaskRunner              $taskRunner
     * @param EventDispatcherInterface $dispatcher
     * @param Filesystem               $filesystem
     * @param Logger                   $logger
     */
    public function __construct(
        TaskRunner $taskRunner,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $this->taskRunner = $taskRunner;
        $this->dispatcher = $dispatcher;
        $this->filesystem = $filesystem;
        $this->logger     = $logger;
    }

    /**
     * @param Build $build
     * @return bool
     * @throws PhpSpecException
     * @throws ProphecyException
     */
    public function initialize(Build $build)
    {
        try {
            $buildDir = $build->getBuildDir();

            $dirs = array($buildDir);
            foreach ($build->getTasks() as $task) {
                $dirs[] = $buildDir . '/' . $task->getCn();
            }

            $this->filesystem->mkdir($dirs);
        } catch (IOExceptionInterface $e) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf('Cannot create directory "%s".', $e->getPath()),
                array('build' => $build->getCn())
            );

            return false;
        }

        $this->activateBuildLogHandler($buildDir . '/build.log');

        $this->filesystem->dumpFile(
            $buildDir . '/suite_config.json',
            $build->toJson()
        );

        try {
            $this->dispatcher->dispatch(Events::BuildInitialized, new BuildEvent($build));
            $this->logger->log(LogLevel::DEBUG, sprintf('Build "%s" initialized.', $build->getCn()));

            return true;
        } catch (\Exception $e) {
            // rethrow phpspec and prophecy exceptions
            if ($e instanceof PhpSpecException || $e instanceof ProphecyException) {
                throw $e;
            }

            $this->logger->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during initialize: %s', $e->getMessage()),
                array('build' => $build->getCn())
            );
            $this->deactivateBuildLogHandler();

            return false;
        }
    }

    /**
     * @param Build  $build
     * @param Result $result
     * @return bool
     * @throws PhpSpecException
     * @throws ProphecyException
     */
    public function run(Build $build, Result $result)
    {
        try {
            $this->logger->log(LogLevel::DEBUG, sprintf('Build "%s" started.', $build->getCn()));
            $this->dispatcher->dispatch(Events::BuildStarted, new BuildRunEvent($build, $result));

            $buildComplete = true;

            foreach ($build->getTasks() as $task) {
                $this->logger->log(LogLevel::DEBUG, sprintf('Task "%s" started.', $task->getCn()));
                $this->dispatcher->dispatch(Events::TaskStarted, new TaskRunEvent($task, $result));

                $taskComplete = $this->taskRunner->run($task, $build, $result);

                $this->dispatcher->dispatch(Events::TaskFinished, new TaskRunEvent($task, $result));
                $this->logger->log(LogLevel::DEBUG, sprintf('Task "%s" finished.', $task->getCn()));

                if (!$taskComplete) {
                    $buildComplete = false;
                    break;
                }

                if ($task->stopOnFailure() && $result->getTaskStatus($task) == Result::FAILED) {
                    break;
                }
            }

            $this->dispatcher->dispatch(Events::BuildFinished, new BuildRunEvent($build, $result));
            $this->logger->log(LogLevel::DEBUG, sprintf('Build "%s" finished.', $build->getCn()));

            return $buildComplete;
        } catch (\Exception $e) {
            // rethrow phpspec and prophecy exceptions
            if ($e instanceof PhpSpecException || $e instanceof ProphecyException) {
                throw $e;
            }

            $this->logger->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during run: %s', $e->getMessage()),
                array('build' => $build->getCn())
            );

            return false;
        }
    }

    /**
     * @param Build  $build
     * @param Result $result
     * @return bool
     * @throws PhpSpecException
     * @throws ProphecyException
     */
    public function cleanup(Build $build, Result $result)
    {
        try {
            $this->filesystem->dumpFile(
                $build->getBuildDir() . '/build_result.json',
                $result->toJson()
            );

            $this->deactivateBuildLogHandler();

            $this->dispatcher->dispatch(Events::BuildCleanedUp, new BuildEvent($build));
            $this->logger->log(LogLevel::DEBUG, sprintf('Build "%s" cleaned up.', $build->getCn()));

            return true;
        } catch (\Exception $e) {
            // rethrow phpspec and prophecy exceptions
            if ($e instanceof PhpSpecException || $e instanceof ProphecyException) {
                throw $e;
            }

            $this->logger->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during cleanup: %s', $e->getMessage()),
                array('build' => $build->getCn())
            );

            return false;
        }
    }

    /**
     * @param string $logfile
     * @throws \RuntimeException
     */
    protected function activateBuildLogHandler($logfile)
    {
        if ($this->buildLogHandler) {
            throw new \RuntimeException('Build log already activated.');
        }

        $this->buildLogHandler = new StreamHandler($logfile);
        $this->logger->pushHandler($this->buildLogHandler);
    }

    /**
     * @throws \RuntimeException
     */
    protected function deactivateBuildLogHandler()
    {
        if ($this->buildLogHandler) {
            $this->logger->popHandler();
            $this->buildLogHandler = null;
        }
    }
}
