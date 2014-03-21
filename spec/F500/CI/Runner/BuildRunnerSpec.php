<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Event\Events;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Runner\TaskRunner;
use F500\CI\Task\Task;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BuildRunnerSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Runner
 */
class BuildRunnerSpec extends ObjectBehavior
{

    function let(
        Build $build,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $suiteJson = <<< EOT
{
    "suite": {
        "name": "Some Suite",
        "cn": "some_suite",
        "class": "F500\\\\CI\\\\Suite\\\\StandardSuite"
    },
    "build": {
        "class": "F500\\\\CI\\\\Build\\\\StandardBuild"
    }
}
EOT;

        $build->getCn()->willReturn('a1b2c3d4');
        $build->getBuildDir()->willReturn('/path/to/build');
        $build->getTasks()->willReturn(array('some_task' => $taskOne, 'other_task' => $taskTwo));
        $build->toJson()->willReturn($suiteJson);

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($taskRunner, $dispatcher, $filesystem, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Runner\BuildRunner');
    }

    function it_creates_directories_while_initializing_a_build(Build $build, Filesystem $filesystem)
    {
        $this->initialize($build)->shouldReturn(true);

        $filesystem->mkdir(Argument::type('array'))->shouldHaveBeenCalled();
    }

    function it_stores_the_suite_config_while_initializing_a_build(Build $build, Filesystem $filesystem)
    {
        $this->initialize($build)->shouldReturn(true);

        $filesystem->dumpFile(Argument::type('string'), Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_activates_a_loghandler_while_initializing_a_build(Build $build, Logger $logger)
    {
        $this->initialize($build)->shouldReturn(true);

        $logger->pushHandler(Argument::type('Monolog\Handler\HandlerInterface'))->shouldHaveBeenCalled();
    }

    function it_dispatches_an_event_while_initializing_a_build(Build $build, EventDispatcherInterface $dispatcher)
    {
        $this->initialize($build)->shouldReturn(true);

        $dispatcher->dispatch(Events::BuildInitialized, Argument::type('F500\CI\Event\BuildEvent'))
            ->shouldHaveBeenCalled();
    }

    function it_runs_a_build_successfully(
        Build $build,
        Result $result,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner
    ) {
        $taskOne->getCn()->willReturn('task_one');
        $taskOne->stopOnFailure()->willReturn(false);

        $taskTwo->getCn()->willReturn('task_two');
        $taskTwo->stopOnFailure()->willReturn(false);

        $taskRunner->run($taskOne, $build, $result)->willReturn(true);
        $taskRunner->run($taskTwo, $build, $result)->willReturn(true);

        $result->getTaskStatus($taskOne)->willReturn(Result::PASSED);
        $result->getTaskStatus($taskTwo)->willReturn(Result::PASSED);

        $this->run($build, $result)->shouldReturn(true);

        $taskRunner->run($taskOne, $build, $result)->shouldHaveBeenCalled();
        $taskRunner->run($taskTwo, $build, $result)->shouldHaveBeenCalled();
    }

    function it_stops_running_a_build_when_a_task_has_borked(
        Build $build,
        Result $result,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner
    ) {
        $taskOne->getCn()->willReturn('task_one');
        $taskOne->stopOnFailure()->willReturn(false);

        $taskRunner->run($taskOne, $build, $result)->willReturn(false);

        $result->getTaskStatus($taskOne)->willReturn(Result::BORKED);

        $this->run($build, $result)->shouldReturn(false);

        $taskRunner->run($taskOne, $build, $result)->shouldHaveBeenCalled();
        $taskRunner->run($taskTwo, $build, $result)->shouldNotHaveBeenCalled();
    }

    function it_dispatches_events_while_running_a_build(
        Build $build,
        Result $result,
        EventDispatcherInterface $dispatcher
    ) {
        $this->run($build, $result)->shouldReturn(false);

        $dispatcher->dispatch(Events::BuildStarted, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskStarted, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskFinished, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::BuildFinished, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
    }

    function it_continues_running_a_build_when_a_task_has_failed_but_does_not_break_on_failure(
        Build $build,
        Result $result,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner,
        EventDispatcherInterface $dispatcher
    ) {
        $taskOne->getCn()->willReturn('task_one');
        $taskOne->stopOnFailure()->willReturn(false);

        $taskTwo->getCn()->willReturn('task_two');
        $taskTwo->stopOnFailure()->willReturn(false);

        $taskRunner->run($taskOne, $build, $result)->willReturn(true);
        $taskRunner->run($taskTwo, $build, $result)->willReturn(true);

        $result->getTaskStatus($taskOne)->willReturn(Result::FAILED);
        $result->getTaskStatus($taskTwo)->willReturn(Result::FAILED);

        $this->run($build, $result)->shouldReturn(true);

        $taskRunner->run($taskOne, $build, $result)->shouldHaveBeenCalled();
        $taskRunner->run($taskTwo, $build, $result)->shouldHaveBeenCalled();

        $dispatcher->dispatch(Events::BuildStarted, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskStarted, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskFinished, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::BuildFinished, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
    }

    function it_stops_running_a_build_when_a_task_has_failed_and_breaks_on_failure(
        Build $build,
        Result $result,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner,
        EventDispatcherInterface $dispatcher
    ) {
        $taskOne->getCn()->willReturn('task_one');
        $taskOne->stopOnFailure()->willReturn(true);

        $taskRunner->run($taskOne, $build, $result)->willReturn(true);

        $result->getTaskStatus($taskOne)->willReturn(Result::FAILED);

        $this->run($build, $result)->shouldReturn(true);

        $taskRunner->run($taskOne, $build, $result)->shouldHaveBeenCalled();
        $taskRunner->run($taskTwo, $build, $result)->shouldNotHaveBeenCalled();

        $dispatcher->dispatch(Events::BuildStarted, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskStarted, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::TaskFinished, Argument::type('F500\CI\Event\TaskRunEvent'))
            ->shouldHaveBeenCalled();
        $dispatcher->dispatch(Events::BuildFinished, Argument::type('F500\CI\Event\BuildRunEvent'))
            ->shouldHaveBeenCalled();
    }

    function it_stores_the_build_result_while_cleaning_up_a_build(Build $build, Result $result, Filesystem $filesystem)
    {
        $this->initialize($build);
        $this->cleanup($build, $result)->shouldReturn(true);

        $filesystem->dumpFile(Argument::type('string'), Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_deactivates_a_loghandler_while_cleaning_up_a_build(Build $build, Result $result, Logger $logger)
    {
        $this->initialize($build);
        $this->cleanup($build, $result)->shouldReturn(true);

        $logger->popHandler()->shouldHaveBeenCalled();
    }

    function it_dispatches_an_event_while_cleaning_up_a_build(
        Build $build,
        Result $result,
        EventDispatcherInterface $dispatcher
    ) {
        $this->initialize($build);
        $this->cleanup($build, $result)->shouldReturn(true);

        $dispatcher->dispatch(Events::BuildCleanedUp, Argument::type('F500\CI\Event\BuildEvent'))
            ->shouldHaveBeenCalled();
    }
}
