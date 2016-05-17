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
     * @var BuildInfo
     */
    private $buildInfo;

    /**
     * @param Suite $suite
     * @param string $buildsDir
     * @param BuildInfo $buildInfo
     */
    public function __construct(Suite $suite, $buildsDir, BuildInfo $buildInfo)
    {
        $this->date      = new \DateTimeImmutable();
        $this->suite     = $suite;
        $this->buildDir  = sprintf('%s/%s/%s', $buildsDir, $this->getSuiteCn(), $this->getCn());
        $this->buildInfo = $buildInfo;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return base_convert($this->getDate()->getTimestamp(), 10, 36);
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
    public function getSuiteCn()
    {
        return $this->suite->getCn();
    }

    /**
     * @return string
     */
    public function getSuiteName()
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

    /**
     * @return string
     */
    public function toJson()
    {
        return $this->suite->toJson();
    }

    public function getAuthor()
    {
        return $this->buildInfo->getAuthor();
    }

    public function getBranch()
    {
        return $this->buildInfo->getBranch();
    }

    public function getComment()
    {
        return $this->buildInfo->getComment();
    }

    public function getCompare()
    {
        return $this->buildInfo->getCompare();
    }

    public function getRepo()
    {
        return $this->buildInfo->getRepo();
    }

}
