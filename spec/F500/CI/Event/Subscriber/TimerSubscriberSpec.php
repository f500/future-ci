<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Build\Result;
use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\TaskRunEvent;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TimerSubscriberSpec
 *
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

    function it_stores_elapsed_time_when_build_finished_event_is_dispatched(BuildRunEvent $event, Result $result)
    {
        $event->getResult()->willReturn($result);

        $this->onBuildFinished($event);

        $result->setElapsedBuildTime(Argument::type('float'))->shouldHaveBeenCalled();
    }

    function it_stores_elapsed_time_when_task_finished_event_is_dispatched(
        Task $task,
        TaskRunEvent $event,
        Result $result
    ) {
        $event->getTask()->willReturn($task);
        $event->getResult()->willReturn($result);

        $this->onTaskFinished($event);

        $result->setElapsedTaskTime($task, Argument::type('float'))->shouldHaveBeenCalled();
    }
}
