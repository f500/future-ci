<?php

namespace F500\CI\Suite;

use F500\CI\Build\Build;
use F500\CI\Run\Toolkit;
use F500\CI\Task\Task;
use F500\CI\Wrapper\Wrapper;

interface Suite
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
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return Build
     */
    public function getActiveBuild();

    /**
     * @param Build $build
     */
    public function setActiveBuild(Build $build);

    /**
     * @return Task[]
     */
    public function getTasks();

    /**
     * @param string $cn
     * @param Task   $task
     * @throws \InvalidArgumentException
     */
    public function addTask($cn, Task $task);

    /**
     * @return Wrapper[]
     */
    public function getWrappers();

    /**
     * @param string $cn
     * @return Wrapper
     */
    public function getWrapper($cn);

    /**
     * @param string  $cn
     * @param Wrapper $wrapper
     */
    public function addWrapper($cn, Wrapper $wrapper);

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit);
}
