<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Event\SuiteEvent;
use F500\CI\Event\TaskEvent;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleOutputSubscriberSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event\Subscriber
 */
class ConsoleOutputSubscriberSpec extends ObjectBehavior
{

    function let(OutputInterface $output)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($output);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\Subscriber\ConsoleOutputSubscriber');
        $this->shouldImplement('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_outputs_when_suite_started_event_is_dispatched(Suite $suite, SuiteEvent $event, OutputInterface $output)
    {
        $event->getSuite()->willReturn($suite);
        $suite->getName()->willReturn('Some Suite');
        $output->writeln(Argument::type('string'))->shouldBeCalled();

        $this->onSuiteStarted($event);
    }

    function it_outputs_when_suite_finished_event_is_dispatched(
        Suite $suite,
        SuiteEvent $event,
        OutputInterface $output
    ) {
        $event->getSuite()->willReturn($suite);
        $suite->getName()->willReturn('Some Suite');
        $output->writeln(Argument::type('string'))->shouldBeCalled();

        $this->onSuiteFinished($event);
    }

    function it_outputs_when_task_started_event_is_dispatched(Task $task, TaskEvent $event, OutputInterface $output)
    {
        $event->getTask()->willReturn($task);
        $task->getName()->willReturn('Some Task');
        $output->writeln(Argument::type('string'))->shouldBeCalled();

        $this->onTaskStarted($event);
    }

    function it_outputs_when_task_finished_event_is_dispatched(Task $task, TaskEvent $event, OutputInterface $output)
    {
        $event->getTask()->willReturn($task);
        $task->getName()->willReturn('Some Task');
        $output->writeln(Argument::type('string'))->shouldBeCalled();

        $this->onTaskFinished($event);
    }
}
