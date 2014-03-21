<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\TaskRunEvent;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleOutputSubscriberSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
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

    function it_writes_output_when_a_build_has_started(
        BuildRunEvent $event,
        Build $build,
        \DateTimeImmutable $date,
        OutputInterface $output
    ) {
        $event->getBuild()->willReturn($build);

        $build->getCn()->willReturn('a1b2c3d4');
        $build->getSuiteName()->willReturn('Some Suite');

        $this->onBuildStarted($event);

        $output->writeln(Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_writes_output_when_a_task_has_started(Task $task, TaskRunEvent $event, OutputInterface $output)
    {
        $event->getTask()->willReturn($task);

        $task->getName()->willReturn('Some Task');

        $this->onTaskStarted($event);

        $output->write(Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_writes_passed_output_when_a_task_has_passed(
        Task $task,
        TaskRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getTask()->willReturn($task);
        $event->getResult()->willReturn($result);

        $result->getTaskStatus($task)->willReturn(Result::PASSED);
        $result->getElapsedTaskTime($task)->willReturn(123456789);

        $this->onTaskFinished($event);

        $output->writeln(Argument::containingString('passed'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }

    function it_writes_failed_output_when_a_task_has_failed(
        Task $task,
        TaskRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getTask()->willReturn($task);
        $event->getResult()->willReturn($result);

        $result->getTaskStatus($task)->willReturn(Result::FAILED);
        $result->getElapsedTaskTime($task)->willReturn(123456789);

        $this->onTaskFinished($event);

        $output->writeln(Argument::containingString('failed'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }

    function it_writes_borked_output_when_a_task_has_borked(
        Task $task,
        TaskRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getTask()->willReturn($task);
        $event->getResult()->willReturn($result);

        $result->getTaskStatus($task)->willReturn(Result::BORKED);
        $result->getElapsedTaskTime($task)->willReturn(123456789);

        $this->onTaskFinished($event);

        $output->writeln(Argument::containingString('borked'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }

    function it_writes_passed_output_when_a_build_has_passed(
        BuildRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getResult()->willReturn($result);

        $result->getBuildStatus()->willReturn(Result::PASSED);
        $result->getElapsedBuildTime()->willReturn(123456789);

        $this->onBuildFinished($event);

        $output->writeln(Argument::containingString('passed'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }

    function it_writes_failed_output_when_a_build_has_failed(
        BuildRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getResult()->willReturn($result);

        $result->getBuildStatus()->willReturn(Result::FAILED);
        $result->getElapsedBuildTime()->willReturn(123456789);

        $this->onBuildFinished($event);

        $output->writeln(Argument::containingString('failed'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }

    function it_writes_borked_output_when_a_build_has_borked(
        BuildRunEvent $event,
        Result $result,
        OutputInterface $output
    ) {
        $event->getResult()->willReturn($result);

        $result->getBuildStatus()->willReturn(Result::BORKED);
        $result->getElapsedBuildTime()->willReturn(123456789);

        $this->onBuildFinished($event);

        $output->writeln(Argument::containingString('borked'))->shouldHaveBeenCalled();
        $output->writeln(Argument::containingString('took'))->shouldHaveBeenCalled();
    }
}
