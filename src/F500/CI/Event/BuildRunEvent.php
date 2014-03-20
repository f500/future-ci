<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BuildRunEvent
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
class BuildRunEvent extends Event
{

    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @param Build  $build
     * @param Result $result
     */
    public function __construct(Build $build, Result $result)
    {
        $this->build  = $build;
        $this->result = $result;
    }

    /**
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }

    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
