<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;

/**
 * Interface Build
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
interface Build
{
    /**
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return Suite
     */
    public function getSuite();

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
