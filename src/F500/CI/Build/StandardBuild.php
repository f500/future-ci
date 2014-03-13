<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Suite\Suite;
use F500\CI\Task\Task;

/**
 * Class StandardBuild
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class StandardBuild implements Build
{

    /**
     * @var \DateTimeImmutable
     */
    protected $date;

    /**
     * @var string
     */
    protected $buildDir;

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @param Suite  $suite
     * @param string $buildsDir
     */
    public function __construct(Suite $suite, $buildsDir)
    {
        $this->date  = new \DateTimeImmutable();
        $this->suite = $suite;

        $this->buildDir = $buildsDir . '/' . $this->getCn();
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->suite->getCn() . $this->date->format('.Y.m.d.H.i.s');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->suite->getName();
    }

    /**
     * @param Task $task
     * @return string
     */
    public function getBuildDir(Task $task = null)
    {
        $buildDir = $this->buildDir;

        if ($task) {
            $buildDir .= '/' . $task->getCn();
        }

        return $buildDir;
    }

    /**
     * @return \F500\CI\Task\Task[]
     */
    public function getTasks()
    {
        return $this->suite->getTasks();
    }
}
