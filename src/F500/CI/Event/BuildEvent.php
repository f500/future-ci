<?php

namespace F500\CI\Event;

use F500\CI\Build\Build;
use Symfony\Component\EventDispatcher\Event;

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
