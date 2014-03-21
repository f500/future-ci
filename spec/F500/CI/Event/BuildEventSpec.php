<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event;

use F500\CI\Build\Build;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BuildEventSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event
 */
class BuildEventSpec extends ObjectBehavior
{

    function let(Build $build)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($build);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\BuildEvent');
    }

    function it_has_a_build()
    {
        $this->getBuild()->shouldReturnAnInstanceOf('F500\CI\Build\Build');
    }
}
