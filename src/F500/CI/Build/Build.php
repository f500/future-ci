<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Metadata\BuildMetadata;
use F500\CI\Run\Toolkit;
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
     * @param Suite $suite
     */
    public function __construct(Suite $suite);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return \DateTimeImmutable
     */
    public function getDate();

    /**
     * @return Suite
     */
    public function getSuite();

    /**
     * @return BuildMetadata
     */
    public function getMetadata();

    /**
     * @param BuildMetadata $metadata
     */
    public function setMetadata(BuildMetadata $metadata);

    /**
     * @return string
     */
    public function getBuildDir();

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function initialize(Toolkit $toolkit);

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit);

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function cleanup(Toolkit $toolkit);
}
