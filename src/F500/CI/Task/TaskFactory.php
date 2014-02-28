<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Suite\Suite;

/**
 * Class TaskFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
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
