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
 * @author    Jasper N. Brouwer <jasper@future500.nl>
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
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getProjectDir();

    /**
     * @return string
     */
    public function getBuildDir();

    /**
     * @return \F500\CI\Task\Task[]
     */
    public function getTasks();
}
