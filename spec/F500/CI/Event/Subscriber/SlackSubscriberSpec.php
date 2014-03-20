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
use F500\CI\Task\Task;
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

    function let(
        BuildRunEvent $event,
        Build $build,
        Task $task,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab,
        Message $message,
        MessageResponse $response
    ) {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($phlack);

        $event->getBuild()->willReturn($build);

        $build->getCn()->willReturn('a1b2c3d4');
        $build->getSuiteName()->willReturn('Some Suite');
        $build->getTasks()->willReturn(array('some_task' => $task));

        $task->getCn()->willReturn('some_task');
        $task->getName()->willReturn('Some Task');

        $phlack->getMessageBuilder()->willReturn($mb);
        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->willReturn($response);

        $mb->setText(Argument::type('string'))->willReturn($mb);
        $mb->createAttachment()->willReturn($ab);
        $mb->create()->willReturn($message);

        $ab->setText(Argument::type('string'))->willReturn($ab);
        $ab->setFallback(Argument::type('string'))->willReturn($ab);
        $ab->setColor(Argument::type('string'))->willReturn($ab);
        $ab->addField(Argument::type('string'), Argument::type('string'), Argument::type('bool'))->willReturn($ab);
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
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::SUCCESSFUL);
        $result->getOverallTaskResult(Argument::type('F500\CI\Task\Task'))->willReturn(Result::SUCCESSFUL);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Passed'))->shouldHaveBeenCalled();
        $ab->setColor('good')->shouldHaveBeenCalled();
        $ab->addField(Argument::type('string'), Argument::type('string'), false)->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_success_message_on_build_failed(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::FAILED);
        $result->getOverallTaskResult(Argument::type('F500\CI\Task\Task'))->willReturn(Result::FAILED);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Failed'))->shouldHaveBeenCalled();
        $ab->setColor('warning')->shouldHaveBeenCalled();
        $ab->addField(Argument::type('string'), Argument::type('string'), false)->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_success_message_on_build_borked(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $result->getOverallBuildResult()->willReturn(Result::INCOMPLETE);
        $result->getOverallTaskResult(Argument::type('F500\CI\Task\Task'))->willReturn(Result::INCOMPLETE);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Borked'))->shouldHaveBeenCalled();
        $ab->setColor('danger')->shouldHaveBeenCalled();
        $ab->addField(Argument::type('string'), Argument::type('string'), false)->shouldHaveBeenCalled();
    }
}
