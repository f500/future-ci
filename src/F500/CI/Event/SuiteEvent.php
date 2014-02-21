<?php

namespace F500\CI\Event;

use F500\CI\Suite\Suite;
use Symfony\Component\EventDispatcher\Event;

class SuiteEvent extends Event
{

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @param Suite $suite
     */
    public function __construct(Suite $suite)
    {
        $this->suite = $suite;
    }

    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }
}
