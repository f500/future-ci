<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

use F500\CI\Build\Result;
use F500\CI\Task\Task;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TaskRunEvent
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
class TaskRunEvent extends Event
{

    /**
     * @var Task
     */
    protected $task;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @param Task   $task
     * @param Result $result
     */
    public function __construct(Task $task, Result $result)
    {
        $this->task   = $task;
        $this->result = $result;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
