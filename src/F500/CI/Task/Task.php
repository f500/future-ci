<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;

/**
 * Interface Task
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
interface Task
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
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return string[]
     */
    public function getWrappers();

    /**
     * @param string[] $cns
     */
    public function setWrappers($cns);

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit);
}
