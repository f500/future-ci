<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Suite\Suite;

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
     * @param Suite $suite
     * @param string $buildsDir
     * @param $buildInfo
     */
    public function __construct(Suite $suite, $buildsDir, BuildInfo $buildInfo);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return \DateTimeImmutable
     */
    public function getDate();

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

    /**
     * @return string
     */
    public function getAuthor();

    /**
     * @return string
     */
    public function getBranch();

    /**
     * @return string
     */
    public function getComment();

    /**
     * @return string
     */
    public function getCompare();

    /**
     * @return string
     */
    public function getRepo();


}
