<?php

namespace spec\F500\CI\Suite;

use F500\CI\Build\Build;
use F500\CI\Run\Toolkit;
use F500\CI\Task\Task;
use F500\CI\Wrapper\Wrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
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

    function it_has_an_active_build_after_setting_it(Build $build)
    {
        $this->setActiveBuild($build);

        $this->getActiveBuild()->shouldReturn($build);
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
            'addTask',
            array('some_task', $task)
        );
    }

    function it_can_add_a_wrapper_to_itself(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);

        $this->getWrappers()->shouldReturn(array('some_wrapper' => $wrapper));
        $this->getWrapper('some_wrapper')->shouldReturn($wrapper);
    }

    function it_fails_adding_a_wrapper_when_cn_already_exists(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);

        $this->shouldThrow('InvalidArgumentException')->during(
            'addWrapper',
            array('some_wrapper', $wrapper)
        );
    }

    function it_does_not_have_a_wrapper_that_has_not_been_added()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getWrapper',
            array('some_wrapper')
        );
    }

    function it_runs_itself(Task $task, Toolkit $toolkit, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

        $task->run($toolkit)->willReturn(true);

        $this->addTask('some_task', $task);
        $this->run($toolkit)->shouldReturn(true);
    }
}
