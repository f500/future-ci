<?php

namespace spec\F500\CI\Task;

use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\TaskFactory');
    }

    function it_creates_a_task(Suite $suite)
    {
        $task = $this->create('F500\CI\Task\DummyTask', 'some_task', $suite);
        $task->shouldHaveType('F500\CI\Task\DummyTask');
        $task->shouldImplement('F500\CI\Task\Task');
    }

    function it_fails_to_create_a_task_when_class_does_not_exist(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('NonExistent\Task', 'some_task', $suite)
        );
    }

    function it_fails_to_create_a_task_when_interface_not_implemented(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('StdClass', 'some_task', $suite)
        );
    }
}
