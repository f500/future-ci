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
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event\Subscriber
 */
class SlackSubscriberSpec extends ObjectBehavior
{

    const TASK_NAME = 'task_name';

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

        $task->getName()->willReturn(self::TASK_NAME);
        $build->getCn()->willReturn('a1b2c3d4');
        $build->getSuiteName()->willReturn('Some Suite');
        $build->getTasks()->willReturn(array('some_task' => $task));
        $build->getCommit()->willReturn(null);

//        $task->getCn()->willReturn('some_task');
//        $task->getName()->willReturn('Some Task');

        $phlack->getMessageBuilder()->willReturn($mb);
        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->willReturn($response);

        $mb->setText(Argument::type('string'))->willReturn($mb);
        $mb->createAttachment()->willReturn($ab);
        $mb->create()->willReturn($message);

        $ab->setText(Argument::type('string'))->willReturn($ab);
        $ab->setFallback(Argument::type('string'))->willReturn($ab);
        $ab->setColor(Argument::type('string'))->willReturn($ab);
        $ab->addField(self::TASK_NAME, Argument::type('string'), Argument::type('bool'))->willReturn($ab);
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

    function it_sends_a_slack_passed_message_on_build_passed(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $message = "Reason why it passed";
        $result->getBuildStatus()->willReturn(Result::PASSED);
        $result->getTaskStatus(Argument::type('F500\CI\Task\Task'))->willReturn(Result::PASSED);
        $result->getTaskMessage(Argument::type('F500\CI\Task\Task'))->willReturn($message);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Passed'))->shouldHaveBeenCalled();
        $ab->setColor('good')->shouldHaveBeenCalled();
        $ab->addField(self::TASK_NAME, $message, false)->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_failed_message_on_build_failed(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $message = "Reason why it failed";
        $result->getBuildStatus()->willReturn(Result::FAILED);
        $result->getTaskStatus(Argument::type('F500\CI\Task\Task'))->willReturn(Result::FAILED);
        $result->getTaskMessage(Argument::type('F500\CI\Task\Task'))->willReturn($message);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Failed'))->shouldHaveBeenCalled();
        $ab->setColor('warning')->shouldHaveBeenCalled();
        $ab->addField(self::TASK_NAME, $message, false)->shouldHaveBeenCalled();
    }

    function it_sends_a_slack_borked_message_on_build_borked(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $message = "Reason why it borked";
        $result->getBuildStatus()->willReturn(Result::BORKED);
        $result->getTaskStatus(Argument::type('F500\CI\Task\Task'))->willReturn(Result::BORKED);
        $result->getTaskMessage(Argument::type('F500\CI\Task\Task'))->willReturn($message);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Borked'))->shouldHaveBeenCalled();
        $ab->setColor('danger')->shouldHaveBeenCalled();
        $ab->addField(self::TASK_NAME, $message, false)->shouldHaveBeenCalled();
    }

    function it_truncates_task_messages_if_more_than_three_lines(
        BuildRunEvent $event,
        Result $result,
        Phlack $phlack,
        MessageBuilder $mb,
        AttachmentBuilder $ab
    ) {
        $event->getResult()->willReturn($result);

        $givenMessage = "This is line 1\nThis is line 2\nThis is line 3\nThis is line 4";
        $receivedMessage = "This is line 1\nThis is line 2\nThis is line 3\n...";
        $result->getBuildStatus()->willReturn(Result::PASSED);
        $result->getTaskStatus(Argument::type('F500\CI\Task\Task'))->willReturn(Result::PASSED);
        $result->getTaskMessage(Argument::type('F500\CI\Task\Task'))->willReturn($givenMessage);
        $result->getElapsedBuildTime()->willReturn(12345678);

        $this->onBuildFinished($event);

        $phlack->send(Argument::type('Crummy\Phlack\Message\Message'))->shouldHaveBeenCalled();

        $mb->setText(Argument::type('string'))->shouldHaveBeenCalled();

        $ab->setFallback(Argument::containingString('Passed'))->shouldHaveBeenCalled();
        $ab->setColor('good')->shouldHaveBeenCalled();
        $ab->addField(self::TASK_NAME, $receivedMessage, false)->shouldHaveBeenCalled();
    }
}
