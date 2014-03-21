<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use F500\CI\Build\Result;
use F500\CI\Task\Task;
use Prophecy\Argument;

/**
 * Class AlwaysPassResultParserSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class AlwaysPassResultParserSpec extends ResultParserSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\AlwaysPassResultParser');
        $this->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_always_determines_that_a_task_has_passed(Task $task, Result $result)
    {
        $result->markTaskAsPassed($task)
            ->willReturn()
            ->shouldBeCalled();

        $this->parse($task, $result);
    }
}
