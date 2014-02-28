<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Event\BuildEvent;
use F500\CI\Event\Events;
use F500\CI\Metadata\BuildMetadata;
use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use Psr\Log\LogLevel;

/**
 * Class StandardBuild
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class StandardBuild implements Build
{

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @var BuildMetadata
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $buildDir;

    /**
     * @param Suite $suite
     */
    public function __construct(Suite $suite)
    {
        $this->date  = new \DateTimeImmutable();
        $this->suite = $suite;

        $suite->setActiveBuild($this);
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->getSuite()->getCn() . $this->getDate()->format('.Y.m.d.H.i.s');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * @return BuildMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param BuildMetadata $metadata
     */
    public function setMetadata(BuildMetadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getBuildDir()
    {
        if (!$this->buildDir) {
            throw new \RuntimeException('Build has not been initialized yet.');
        }

        return $this->buildDir;
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     * @throws \RuntimeException
     */
    public function initialize(Toolkit $toolkit)
    {
        try {
            $this->buildDir = $toolkit->getBuildsDir() . '/' . $this->getCn();

            $toolkit->getFilesystem()->mkdir($this->buildDir);
            if (!$toolkit->getFilesystem()->exists($this->buildDir)) {
                $toolkit->getLogger()->log(
                    LogLevel::ERROR,
                    sprintf('Cannot create dir "%s".', $this->buildDir),
                    array('build' => $this->getCn())
                );

                return false;
            }

            foreach ($this->getSuite()->getTasks() as $task) {
                $dir = $this->buildDir . '/' . $task->getCn();

                $toolkit->getFilesystem()->mkdir($dir);
                if (!$toolkit->getFilesystem()->exists($dir)) {
                    $toolkit->getLogger()->log(
                        LogLevel::ERROR,
                        sprintf('Cannot create dir "%s".', $dir),
                        array('build' => $this->getCn())
                    );

                    return false;
                }
            }

            $toolkit->activateBuildLogHandler($this->buildDir . '/build.log');

            $toolkit->getDispatcher()->dispatch(Events::BuildInitialized, new BuildEvent($this));
            $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" initialized.', $this->getCn()));

            return true;
        } catch (\Exception $e) {
            $toolkit->getLogger()->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during initialize: %s', $e->getMessage()),
                array('build' => $this->getCn())
            );

            return false;
        }
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit)
    {
        try {
            $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" started.', $this->getCn()));
            $toolkit->getDispatcher()->dispatch(Events::BuildStarted, new BuildEvent($this));

            $result = $this->suite->run($toolkit);

            $toolkit->getDispatcher()->dispatch(Events::BuildFinished, new BuildEvent($this));
            $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" finished.', $this->getCn()));

            return $result;
        } catch (\Exception $e) {
            $toolkit->getLogger()->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during run: %s', $e->getMessage()),
                array('build' => $this->getCn())
            );

            return false;
        }
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function cleanup(Toolkit $toolkit)
    {
        try {
            $toolkit->deactivateBuildLogHandler();

            $toolkit->getDispatcher()->dispatch(Events::BuildCleanedUp, new BuildEvent($this));
            $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" cleaned up.', $this->getCn()));

            return true;
        } catch (\Exception $e) {
            $toolkit->getLogger()->log(
                LogLevel::CRITICAL,
                sprintf('An exception occurred during cleanup: %s', $e->getMessage()),
                array('build' => $this->getCn())
            );

            return false;
        }
    }
}
