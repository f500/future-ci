<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

/**
 * Class ResultParserFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class ResultParserFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @return ResultParser
     * @throws \InvalidArgumentException
     */
    public function createResultParser($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create result-parser, class "%s" does not exist.',
                    $class
                )
            );
        }

        $resultParser = new $class($cn);

        if (!$resultParser instanceof ResultParser) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create result-parser, class "%s" does not implement F500\CI\Task\ResultParser.',
                    $class
                )
            );
        }

        return $resultParser;
    }
}
