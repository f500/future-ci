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
     * @var array
     */
    protected $metadata;

    /**
     * @var array
     */
    protected $statuses;

    /**
     * @var array
     */
    protected $results;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Build      $build
     * @param Filesystem $filesystem
     */
    public function __construct(Build $build, Filesystem $filesystem)
    {
        $this->metadata = array(
            'suite' => array(
                'cn'   => $build->getSuiteCn(),
                'name' => $build->getSuiteName()
            ),
            'build' => array(
                'cn'   => $build->getCn(),
                'date' => $build->getDate()->format('c'),
                'dir'  => $build->getBuildDir(),
                'time' => null
            ),
            'tasks' => array()
        );

        foreach ($build->getTasks() as $task) {
            $this->metadata['tasks'][$task->getCn()] = array(
                'cn'   => $task->getCn(),
                'name' => $task->getName(),
                'time' => null
            );
        }

        $this->statuses = array(
            'build' => null,
            'tasks' => array()
        );

        $this->results = array();

        $this->filesystem = $filesystem;
    }

    /**
     * @param Task $task
     * @return string
     */
    public function getBuildDir(Task $task = null)
    {
        $buildDir = $this->metadata['build']['dir'];

        if ($task) {
            $buildDir .= '/' . $task->getCn();
        }

        return $buildDir;
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
     * @param Task   $task
     * @param string $name
     * @param mixed  $value
     */
    public function addAdditionalResult(Task $task, $name, $value)
    {
        $this->results[$task->getCn()][$name] = $value;
    }

    /**
     * @param Task $task
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getResults(Task $task)
    {
        if (!isset($this->results[$task->getCn()])) {
            throw new \InvalidArgumentException(sprintf('Task %s has no results yet.', $task->getCn()));
        }

        return $this->results[$task->getCn()];
    }

    /**
     * @param Task $task
     * @param string $message
     */
    public function markTaskAsPassed(Task $task, $message = '')
    {
        $this->statuses['tasks'][$task->getCn()] = self::PASSED;
        $this->addAdditionalResult($task, 'message', $message);
    }

    /**
     * @param Task   $task
     * @param string $message
     */
    public function markTaskAsFailed(Task $task, $message = '')
    {
        $this->statuses['tasks'][$task->getCn()] = self::FAILED;
        $this->addAdditionalResult($task, 'message', $message);
    }

    /**
     * @param Task $task
     * @param string $message
     */
    public function markTaskAsBorked(Task $task, $message = '')
    {
        $this->statuses['tasks'][$task->getCn()] = self::BORKED;
        $this->addAdditionalResult($task, 'message', $message);
    }

    /**
     * @return string
     */
    public function getBuildStatus()
    {
        $this->determineBuildStatus();

        return $this->statuses['build'];
    }

    /**
     * @param Task $task
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getTaskStatus(Task $task)
    {
        if (!isset($this->statuses['tasks'][$task->getCn()])) {
            throw new \InvalidArgumentException(sprintf('Task %s has no status yet.', $task->getCn()));
        }

        return $this->statuses['tasks'][$task->getCn()];
    }

    /**
     * Returns the message that came with the build status for the given task.
     *
     * @param Task $task
     *
     * @return string
     */
    public function getTaskMessage(Task $task)
    {
        return isset($this->results[$task->getCn()]['message'])
            ? $this->results[$task->getCn()]['message']
            : '';
    }

    /**
     * @param float $time
     */
    public function setElapsedBuildTime($time)
    {
        $this->metadata['build']['time'] = $time;
    }

    /**
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getElapsedBuildTime()
    {
        if (!isset($this->metadata['build']['time'])) {
            throw new \InvalidArgumentException(sprintf('Build has no elapsed time yet.'));
        }

        return $this->metadata['build']['time'];
    }

    /**
     * @param Task  $task
     * @param float $time
     */
    public function setElapsedTaskTime(Task $task, $time)
    {
        $this->metadata['tasks'][$task->getCn()]['time'] = $time;
    }

    /**
     * @param Task $task
     * @return float
     * @throws \InvalidArgumentException
     */
    public function getElapsedTaskTime(Task $task)
    {
        if (!isset($this->metadata['tasks'][$task->getCn()]['time'])) {
            throw new \InvalidArgumentException(sprintf('Task %s has no elapsed time yet.', $task->getCn()));
        }

        return $this->metadata['tasks'][$task->getCn()]['time'];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $this->determineBuildStatus();

        return json_encode(
            array(
                'metadata' => $this->metadata,
                'statuses' => $this->statuses,
                'results'  => $this->results
            ),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    protected function determineBuildStatus()
    {
        if (!$this->statuses['build']) {
            $this->statuses['build'] = self::PASSED;

            foreach ($this->statuses['tasks'] as $taskStatus) {
                if ($taskStatus == self::BORKED) {
                    $this->statuses['build'] = self::BORKED;
                    break;
                } elseif ($taskStatus == self::FAILED) {
                    $this->statuses['build'] = self::FAILED;
                }
            }
        }
    }
}
