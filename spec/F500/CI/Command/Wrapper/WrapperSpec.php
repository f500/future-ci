<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command\Wrapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class WrapperSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Wrapper
 */
abstract class WrapperSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_wrapper');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_wrapper');
    }
}
