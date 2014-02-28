<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Build\Build;
use F500\CI\Event\BuildEvent;
use F500\CI\Event\SuiteEvent;
use F500\CI\Event\TaskEvent;
use F500\CI\Metadata\BuildMetadata;
use F500\CI\Metadata\SuiteMetadata;
use F500\CI\Metadata\TaskMetadata;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TimerSubscriberSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event\Subscriber
 */
class TimerSubscriberSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\Subscriber\TimerSubscriber');
        $this->shouldImplement('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_stores_elapsed_time_in_metadata_when_build_finished_event_is_dispatched(
        Build $build,
        BuildMetadata $metadata,
        BuildEvent $event
    ) {
        $event->getBuild()->willReturn($build);
        $build->getMetadata()->willReturn($metadata);
        $metadata->setElapsedTime(Argument::type('float'))->shouldBeCalled();

        $this->onBuildFinished($event);
    }

    function it_stores_elapsed_time_in_metadata_when_suite_finished_event_is_dispatched(
        Suite $suite,
        SuiteMetadata $metadata,
        SuiteEvent $event
    ) {
        $event->getSuite()->willReturn($suite);
        $suite->getMetadata()->willReturn($metadata);
        $metadata->setElapsedTime(Argument::type('float'))->shouldBeCalled();

        $this->onSuiteFinished($event);
    }

    function it_stores_elapsed_time_in_metadata_when_task_finished_event_is_dispatched(
        Task $task,
        TaskMetadata $metadata,
        TaskEvent $event
    ) {
        $event->getTask()->willReturn($task);
        $task->getMetadata()->willReturn($metadata);
        $metadata->setElapsedTime(Argument::type('float'))->shouldBeCalled();

        $this->onTaskFinished($event);
    }
}
