<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Suite\Suite;
use F500\CI\Vcs\Commit;

/**
 * Interface Build
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
interface Build
{

    /**
     * @param Suite  $suite
     * @param string $buildsDir
     */
    public function __construct(Suite $suite, $buildsDir);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return \DateTimeImmutable
     */
    public function getDate();

    /**
     * Registers information on the commit that initiated this build.
     *
     * @param Commit $commit
     *
     * @return $this
     */
    public function initiatedBy(Commit $commit);

    /**
     * Returns the commit that initiated this build or null if this is unknown or not applicable.
     *
     * @return Commit|null
     */
    public function getCommit();

    /**
     * @return string
     */
    public function getSuiteCn();

    /**
     * @return string
     */
    public function getSuiteName();

    /**
     * @return string
     */
    public function getBuildDir();

    /**
     * @return \F500\CI\Task\Task[]
     */
    public function getTasks();

    /**
     * @return string
     */
    public function toJson();
}
