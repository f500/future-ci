<?php

namespace F500\CI\Wrapper;

use F500\CI\Suite\Suite;

interface Wrapper
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
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);
}
