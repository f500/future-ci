<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

use F500\CI\Suite\Suite;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SuiteEvent
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
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
