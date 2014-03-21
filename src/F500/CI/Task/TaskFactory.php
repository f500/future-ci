<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

/**
 * Class TaskFactory
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class TaskFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @return Task
     * @throws \InvalidArgumentException
     */
    public function createTask($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cannot create task, class "%s" does not exist.', $class));
        }

        $task = new $class($cn);

        if (!$task instanceof Task) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create suite, class "%s" does not implement F500\CI\Task\Task.',
                    $class
                )
            );
        }

        return $task;
    }
}
