<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TaskFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class TaskFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\TaskFactory');
    }

    function it_creates_a_task()
    {
        $task = $this->createTask('F500\CI\Task\Dummy\DummyTask', 'some_task');
        $task->shouldHaveType('F500\CI\Task\Dummy\DummyTask');
        $task->shouldImplement('F500\CI\Task\Task');
    }

    function it_fails_to_create_a_task_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createTask',
            array('NonExistent\Task', 'some_task')
        );
    }

    function it_fails_to_create_a_task_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createTask',
            array('StdClass', 'some_task')
        );
    }
}
