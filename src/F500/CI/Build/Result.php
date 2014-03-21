<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Command\Command;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\Task;

/**
 * Class Result
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class Result
{

    /**
     * Run correctly and passed.
     */
    const PASSED = 'passed';

    /**
     * Run correctly and failed.
     */
    const FAILED = 'failed';

    /**
     * Run could not be completed.
     */
    const BORKED = 'borked';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $buildDir;

    /**
     * @var array
     */
    protected $results;

    /**
     * @var array
     */
    protected $elapsedTimes;

    /**
     * @param Filesystem $filesystem
     * @param string     $buildDir
     */
    public function __construct(Filesystem $filesystem, $buildDir)
    {
        $this->filesystem   = $filesystem;
        $this->buildDir     = $buildDir;
        $this->results      = array();
        $this->elapsedTimes = array();
    }

    /**
     * @param Task    $task
     * @param Command $command
     */
    public function addCommandResult(Task $task, Command $command)
    {
        $this->results[$task->getCn()]['commands'][$command->getId()] = array(
            'task'        => $task->getCn(),
            'command_id'  => $command->getId(),
            'command'     => $command->stringify(),
            'result_code' => $command->getResultCode(),
            'output'      => $command->getOutput()
        );
    }

    /**
     * @param Task $task
     */
    public function markTaskAsPassed(Task $task)
    {
        $this->results[$task->getCn()]['result'] = self::PASSED;
    }

    /**
     * @param Task $task
     */
    public function markTaskAsFailed(Task $task)
    {
        $this->results[$task->getCn()]['result'] = self::FAILED;
    }

    /**
     * @param Task $task
     */
    public function markTaskAsBorked(Task $task)
    {
        $this->results[$task->getCn()]['result'] = self::BORKED;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Task $task
     * @return string
     */
    public function getBuildDir(Task $task = null)
    {
        $buildDir = $this->buildDir;

        if ($task) {
            $buildDir .= '/' . $task->getCn();
        }

        return $buildDir;
    }

    /**
     * @return array
     */
    public function getBuildResults()
    {
        return $this->results;
    }

    /**
     * @return string
     */
    public function getBuildStatus()
    {
        $result = self::PASSED;

        foreach ($this->results as $taskResults) {
            if ($taskResults['result'] == self::BORKED) {
                return self::BORKED;
            } elseif ($taskResults['result'] == self::FAILED) {
                $result = self::FAILED;
            }
        }

        return $result;
    }

    /**
     * @param Task $task
     * @return array
     */
    public function getTaskResults(Task $task)
    {
        return $this->results[$task->getCn()];
    }

    /**
     * @param Task $task
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTaskStatus(Task $task)
    {
        if (!isset($this->results[$task->getCn()]['result'])) {
            throw new \InvalidArgumentException(sprintf('Task %s has no result yet.', $task->getCn()));
        }

        return $this->results[$task->getCn()]['result'];
    }

    /**
     * @param float $time
     */
    public function setElapsedBuildTime($time)
    {
        $this->elapsedTimes['build'] = $time;
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getElapsedBuildTime()
    {
        if (!isset($this->elapsedTimes['build'])) {
            throw new \InvalidArgumentException(sprintf('Build has no elapsed time yet.'));
        }

        return $this->elapsedTimes['build'];
    }

    /**
     * @param Task  $task
     * @param float $time
     */
    public function setElapsedTaskTime(Task $task, $time)
    {
        $this->elapsedTimes['tasks'][$task->getCn()] = $time;
    }

    /**
     * @param Task $task
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getElapsedTaskTime(Task $task)
    {
        if (!isset($this->elapsedTimes['tasks'][$task->getCn()])) {
            throw new \InvalidArgumentException(sprintf('Task %s has no elapsed time yet.', $task->getCn()));
        }

        return $this->elapsedTimes['tasks'][$task->getCn()];
    }
}
