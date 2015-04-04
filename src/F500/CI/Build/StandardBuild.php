<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Suite\StandardSuite;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use F500\CI\Vcs\Commit;

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
     * @var Commit|null The commit that initiated this build or null if this is unknown or not applicable.
     */
    private $commit;

    /**
     * @param Suite  $suite
     * @param string $buildsDir
     */
    public function __construct(Suite $suite, $buildsDir)
    {
        $this->date     = new \DateTimeImmutable();
        $this->suite    = $suite;
        $this->buildDir = sprintf('%s/%s/%s', $buildsDir, $this->getSuiteCn(), $this->getCn());
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
     * Returns the name of the directory where the sources for this build are located.
     *
     * @return string
     */
    public function getProjectDir()
    {
        $config = $this->suite->getConfig();

        return isset($config['root_dir']) ? $config['root_dir'] : null;
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
     * Registers information on the commit that initiated this build.
     *
     * @param Commit $commit
     *
     * @return $this;
     */
    public function initiatedBy(Commit $commit)
    {
        $this->commit = $commit;

        return $this;
    }

    /**
     * Returns the commit that initiated this build or null if this is unknown or not applicable.
     *
     * @return Commit|null
     */
    public function getCommit()
    {
        return $this->commit;
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
}
