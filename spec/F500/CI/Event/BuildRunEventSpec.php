<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BuildRunEventSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event
 */
class BuildRunEventSpec extends ObjectBehavior
{

    function let(Build $build, Result $result)
    {
        $this->beConstructedWith($build, $result);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\BuildRunEvent');
    }

    function it_has_a_build()
    {
        $this->getBuild()->shouldReturnAnInstanceOf('F500\CI\Build\Build');
    }

    function it_has_a_result()
    {
        $this->getResult()->shouldReturnAnInstanceOf('F500\CI\Build\Result');
    }
}
