<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Task\ResultParser;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TaskSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
abstract class TaskSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_task');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_task');
    }

    function it_has_a_name_after_setting_it()
    {
        $this->setName('Some Task');

        $this->getName()->shouldReturn('Some Task');
    }

    function it_does_not_have_resultparsers_initially()
    {
        $this->getResultParsers()->shouldReturn(array());
    }

    function its_resultparsers_can_be_added_to(ResultParser $resultParser)
    {
        $this->addResultParser('some_parser', $resultParser);
        $this->getResultParsers()->shouldReturn(array('some_parser' => $resultParser));
    }

    function it_does_not_have_wrappers_initially()
    {
        $this->getWrappers()->shouldReturn(array());
    }

    function its_wrappers_can_be_added_to(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);
        $this->getWrappers()->shouldReturn(array('some_wrapper' => $wrapper));
    }

    function it_does_not_stop_on_failure_initially()
    {
        $this->stopOnFailure()->shouldReturn(false);
    }

    function it_stops_on_failure_after_setting_it()
    {
        $this->setStopOnFailure(true);

        $this->stopOnFailure()->shouldReturn(true);
    }
}
