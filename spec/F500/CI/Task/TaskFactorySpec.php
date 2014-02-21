<?php

namespace spec\F500\CI\Task;

use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TaskFactorySpec extends ObjectBehavior
{

    function let(CommandFactory $commandFactory, ProcessFactory $processFactory)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($commandFactory, $processFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\TaskFactory');
    }

    function it_creates_a_task()
    {
        $task = $this->create('F500\CI\Task\DummyTask', 'some_task');
        $task->shouldHaveType('F500\CI\Task\DummyTask');
        $task->shouldImplement('F500\CI\Task\Task');
    }

    function it_fails_to_create_a_task_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('NonExistent\Task', 'some_task')
        );
    }

    function it_fails_to_create_a_task_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('StdClass', 'some_task')
        );
    }
}
