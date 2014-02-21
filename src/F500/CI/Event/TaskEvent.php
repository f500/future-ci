<?php

namespace F500\CI\Event;

use F500\CI\Task\Task;
use Symfony\Component\EventDispatcher\Event;

class TaskEvent extends Event
{

    /**
     * @var Task
     */
    protected $task;

    /**
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }
}
