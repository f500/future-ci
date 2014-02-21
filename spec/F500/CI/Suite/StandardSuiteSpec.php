<?php

namespace spec\F500\CI\Suite;

use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StandardSuiteSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_suite');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Suite\StandardSuite');
        $this->shouldImplement('F500\CI\Suite\Suite');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_suite');
    }

    function it_has_a_name_after_setting_it()
    {
        $this->setName('Some Suite');

        $this->getName()->shouldReturn('Some Suite');
    }

    function it_can_add_a_task_to_itself(Task $task)
    {
        $this->addTask('some_task', $task);

        $this->getTasks()->shouldReturn(array('some_task' => $task));
    }

    function it_fails_adding_a_task_when_cn_already_exists(Task $task)
    {
        $this->addTask('some_task', $task);

        $this->shouldThrow('InvalidArgumentException')->during(
            'addTask', array('some_task', $task)
        );
    }

    function it_can_remove_a_task_from_itself(Task $task)
    {
        $this->addTask('some_task', $task);
        $this->removeTask('some_task');

        $this->getTasks()->shouldReturn(array());
    }

    function it_fails_removing_a_task_when_the_cn_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'removeTask', array('some_task')
        );
    }

    function it_runs_itself(Task $task, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $task->run($dispatcher, $logger)
            ->willReturn(true)
            ->shouldBeCalled();

        $dispatcher->dispatch(Argument::type('string'), Argument::type('Symfony\Component\EventDispatcher\Event'))
            ->will(
                function ($args) {
                    return $args[1];
                }
            )
            ->shouldBeCalled();

        $logger->log(Argument::type('string'), Argument::type('string'))
            ->willReturn(true)
            ->shouldBeCalled();

        $this->addTask('some_task', $task);
        $this->run($dispatcher, $logger)->shouldReturn(true);
    }
}
