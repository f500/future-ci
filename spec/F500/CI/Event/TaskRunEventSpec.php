<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event;

use F500\CI\Build\Result;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TaskRunEventSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event
 */
class TaskRunEventSpec extends ObjectBehavior
{

    function let(Task $task, Result $result)
    {
        $this->beConstructedWith($task, $result);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\TaskRunEvent');
    }

    function it_has_a_build()
    {
        $this->getTask()->shouldReturnAnInstanceOf('F500\CI\Task\Task');
    }

    function it_has_a_result()
    {
        $this->getResult()->shouldReturnAnInstanceOf('F500\CI\Build\Result');
    }
}
