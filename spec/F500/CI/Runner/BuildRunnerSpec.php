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
 * @author    Jasper N. Brouwer <jasper@future500.nl>
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
        $build->getCn()->willReturn('a1b2c3d4');
        $build->getBuildDir()->willReturn('/path/to/build');
        $build->getTasks()->willReturn(array('some_task' => $taskOne, 'other_task' => $taskTwo));

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($taskRunner, $dispatcher, $filesystem, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Runner\BuildRunner');
    }

    function it_initializes_a_build(
        Build $build,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $this->initialize($build)->shouldReturn(true);

        $dispatcher->dispatch(Events::BuildInitialized, Argument::type('F500\CI\Event\BuildEvent'))
            ->shouldHaveBeenCalled();
        $filesystem->mkdir(Argument::type('array'))
            ->shouldHaveBeenCalled();
        $logger->pushHandler(Argument::type('Monolog\Handler\HandlerInterface'))
            ->shouldHaveBeenCalled();
    }

    function it_runs_a_build_successfully(
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

        $result->getOverallTaskResult($taskOne)->willReturn(Result::SUCCESSFUL);
        $result->getOverallTaskResult($taskTwo)->willReturn(Result::SUCCESSFUL);

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

    function it_stops_running_a_build_when_a_task_is_incomplete(
        Build $build,
        Result $result,
        Task $taskOne,
        Task $taskTwo,
        TaskRunner $taskRunner,
        EventDispatcherInterface $dispatcher
    ) {
        $taskOne->getCn()->willReturn('task_one');
        $taskOne->stopOnFailure()->willReturn(false);

        $taskRunner->run($taskOne, $build, $result)->willReturn(false);

        $result->getOverallTaskResult($taskOne)->willReturn(Result::INCOMPLETE);

        $this->run($build, $result)->shouldReturn(false);

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

        $result->getOverallTaskResult($taskOne)->willReturn(Result::FAILED);
        $result->getOverallTaskResult($taskTwo)->willReturn(Result::FAILED);

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

        $result->getOverallTaskResult($taskOne)->willReturn(Result::FAILED);

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

    function it_cleans_a_build_up(Build $build, EventDispatcherInterface $dispatcher, Logger $logger)
    {
        $this->initialize($build);

        $this->cleanup($build)->shouldReturn(true);

        $dispatcher->dispatch(Events::BuildCleanedUp, Argument::type('F500\CI\Event\BuildEvent'))
            ->shouldHaveBeenCalled();
        $logger->popHandler()
            ->shouldHaveBeenCalled();
    }
}
