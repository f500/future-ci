<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\Wrapper\Wrapper;

/**
 * Interface Task
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
interface Task
{

    /**
     * @param string $cn
     */
    public function __construct($cn);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param string       $cn
     * @param ResultParser $resultParser
     */
    public function addResultParser($cn, ResultParser $resultParser);

    /**
     * @return ResultParser[]
     */
    public function getResultParsers();

    /**
     * @param string                           $cn
     * @param \F500\CI\Command\Wrapper\Wrapper $wrapper
     */
    public function addWrapper($cn, Wrapper $wrapper);

    /**
     * @return Wrapper[]
     */
    public function getWrappers();

    /**
     * @return bool
     */
    public function stopOnFailure();

    /**
     * @param bool $stop
     */
    public function setStopOnFailure($stop);

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory);
}
