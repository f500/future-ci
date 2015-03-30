<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Suite;

use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Task\Task;

/**
 * Interface Suite
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Suite
 */
interface Suite
{

    /**
     * @param string $cn
     * @param array  $config
     */
    public function __construct($cn, array $config);

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
     * @param string $cn
     * @param Task   $task
     * @throws \InvalidArgumentException
     */
    public function addTask($cn, Task $task);

    /**
     * @return Task[]
     */
    public function getTasks();

    /**
     * @param string                           $cn
     * @param \F500\CI\Command\Wrapper\Wrapper $wrapper
     */
    public function addWrapper($cn, Wrapper $wrapper);

    /**
     * @return \F500\CI\Command\Wrapper\Wrapper[]
     */
    public function getWrappers();

    /**
     * @param string $cn
     * @return \F500\CI\Command\Wrapper\Wrapper
     */
    public function getWrapper($cn);

    public function getConfig();

    /**
     * @return string
     */
    public function toJson();
}
