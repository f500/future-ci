<?php

namespace F500\CI\Task;

use F500\CI\Suite\Suite;

class TaskFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @param Suite  $suite
     * @return Task
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn, Suite $suite)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" for task "%s" does not exist.', $class, $cn));
        }

        $task = new $class($cn, $suite);

        if (!$task instanceof Task) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" for task "%s" should implement F500\CI\Task\Task.',
                $class,
                $cn
            ));
        }

        return $task;
    }
}
