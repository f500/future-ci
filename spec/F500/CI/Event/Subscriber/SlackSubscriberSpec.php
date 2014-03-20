<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Event\BuildRunEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SlackOutputSubscriberSpec
 *
 * @author    Ramon de la Fuente <ramon@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event\Subscriber
 */
class SlackSubscriberSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\Subscriber\SlackSubscriber');
        $this->shouldImplement('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_sends_a_slack_message_when_build_started_event_is_dispatched(BuildRunEvent $event, Build $build)
    {
        $event->getBuild()->willReturn($build);

        $this->onBuildStarted($event);
    }

    function it_sends_a_slack_message_when_build_finished_event_is_dispatched(BuildRunEvent $event, Result $result)
    {
        $event->getResult()->willReturn($result);

        $this->onBuildFinished($event);
    }

}
