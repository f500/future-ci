<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use Crummy\Phlack\Bridge\Guzzle\Response\MessageResponse;
use Crummy\Phlack\Builder\AttachmentBuilder;
use Crummy\Phlack\Builder\MessageBuilder;
use Crummy\Phlack\Message\Message;
use Crummy\Phlack\Phlack;
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

    function let(Phlack $phlack, BuildRunEvent $event, Build $build, MessageBuilder $mb, AttachmentBuilder $ab, Message $message, MessageResponse $response)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($phlack);

        $event->getBuild()->willReturn($build);

        $phlack->getMessageBuilder()->willReturn($mb);
        $mb->createAttachment()->willReturn($ab);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->willReturn($response);

        $mb->setText(Argument::type('string'))->willReturn($mb);
        $mb->create()->willReturn($message);

        $ab->setText(Argument::type('string'))->willReturn($ab);
        $ab->setPretext(Argument::type('string'))->willReturn($ab);
        $ab->setColor(Argument::type('string'))->willReturn($ab);
        $ab->end()->willReturn($mb);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\Subscriber\SlackSubscriber');
        $this->shouldImplement('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_sends_a_slack_message_on_build_started(
        BuildRunEvent $event,
        Phlack $phlack
    ) {
        $this->onBuildStarted($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_success_message_on_build_succeeded(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::SUCCESSFUL);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();
        $ab->setPretext(Argument::type('string'))->shouldHaveBeenCalled();
        $ab->setColor('good')->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_success_message_on_build_failed(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::FAILED);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();
        $ab->setPretext(Argument::type('string'))->shouldHaveBeenCalled();
        $ab->setColor('warning')->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_success_message_on_build_incomplete(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::INCOMPLETE);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();
        $ab->setPretext(Argument::type('string'))->shouldHaveBeenCalled();
        $ab->setColor('danger')->shouldHaveBeenCalled();
    }
}
