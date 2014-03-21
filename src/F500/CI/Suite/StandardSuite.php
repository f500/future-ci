<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Suite;

use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Event\SuiteEvent;
use F500\CI\Task\Task;

/**
 * Class StandardSuite
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Suite
 */
class StandardSuite implements Suite
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Task[]
     */
    protected $tasks;

    /**
     * @var Wrapper[]
     */
    protected $wrappers;

    /**
     * @param string $cn
     */
    public function __construct($cn)
    {
        $this->cn    = $cn;
        $this->tasks = array();
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $cn
     * @param Task   $task
     * @throws \InvalidArgumentException
     */
    public function addTask($cn, Task $task)
    {
        if (isset($this->tasks[$cn])) {
            throw new \InvalidArgumentException(sprintf('Task "%s" already added.', $cn));
        }

        $this->tasks[$cn] = $task;
    }

    /**
     * @return Task[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param string  $cn
     * @param Wrapper $wrapper
     * @throws \InvalidArgumentException
     */
    public function addWrapper($cn, Wrapper $wrapper)
    {
        if (isset($this->wrappers[$cn])) {
            throw new \InvalidArgumentException(sprintf('Wrapper "%s" already added.', $cn));
        }

        $this->wrappers[$cn] = $wrapper;
    }

    /**
     * @return Wrapper[]
     */
    public function getWrappers()
    {
        return $this->wrappers;
    }

    /**
     * @param string $cn
     * @return Wrapper
     * @throws \InvalidArgumentException
     */
    public function getWrapper($cn)
    {
        if (!isset($this->wrappers[$cn])) {
            throw new \InvalidArgumentException(sprintf('Wrapper "%s" hasn\'t been added.', $cn));
        }

        return $this->wrappers[$cn];
    }
}
