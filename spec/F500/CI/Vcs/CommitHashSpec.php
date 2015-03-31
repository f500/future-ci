<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Vcs;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CommitHashSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Suite
 */
class CommitHashSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(sha1('some_commit'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Vcs\CommitHash');
        $this->shouldImplement('F500\CI\Vcs\CommitId');
    }

    function it_has_a_value()
    {
        $this->__toString()->shouldReturn(sha1('some_commit'));
    }
}
