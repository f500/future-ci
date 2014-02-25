<?php

namespace F500\CI\Build;

use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;

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
