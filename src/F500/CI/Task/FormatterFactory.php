<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

/**
 * Class FormatterFactory
 *
 * @copyright 2015 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class FormatterFactory
{
    /**
     * @param string $class
     * @param string $cn
     * @return Formatter
     * @throws \InvalidArgumentException
     */
    public function createFormatter($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create formatter, class "%s" does not exist.',
                    $class
                )
            );
        }

        $formatter = new $class($cn);

        if (!$formatter instanceof Formatter) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create formatter, class "%s" does not implement F500\CI\Task\Formatter.',
                    $class
                )
            );
        }

        return $formatter;
    }
}
