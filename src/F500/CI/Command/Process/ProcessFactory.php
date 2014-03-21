<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Process;

use Symfony\Component\Process\Process;

/**
 * Class ProcessFactory
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
class ProcessFactory
{

    /**
     * @var string
     */
    protected $processClass;

    /**
     * @param string $processClass
     */
    public function __construct($processClass)
    {
        $this->processClass = $processClass;
    }

    /**
     * @param array  $args
     * @param string $cwd
     * @param array  $env
     * @param string $input
     * @param float  $timeout
     * @param array  $options
     * @return \Symfony\Component\Process\Process
     * @throws \RuntimeException
     */
    public function createProcess(
        array $args,
        $cwd = null,
        $env = null,
        $input = null,
        $timeout = null,
        $options = array()
    ) {
        $class = $this->processClass;

        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Cannot create process, class "%s" does not exist.', $class));
        }

        $process = new $class(implode(' ', $args), $cwd, $env, $input, $timeout, $options);

        if (!$process instanceof Process) {
            throw new \RuntimeException(
                sprintf(
                    'Cannot create process, class "%s" is not an instance of Symfony\Component\Process\Process.',
                    $class
                )
            );
        }

        return $process;
    }
}
