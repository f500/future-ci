<?php

namespace F500\CI\Wrapper;

use F500\CI\Command\Command;

interface Wrapper
{

    /**
     * @param string $cn
     */
    public function __construct($cn);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

}
