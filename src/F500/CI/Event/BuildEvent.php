<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

use F500\CI\Build\Build;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BuildEvent
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
class BuildEvent extends Event
{

    /**
     * @var Build
     */
    protected $build;

    /**
     * @param Build $build
     */
    public function __construct(Build $build)
    {
        $this->build = $build;
    }

    /**
     * @return Build
     */
    public function getBuild()
    {
        return $this->build;
    }
}
