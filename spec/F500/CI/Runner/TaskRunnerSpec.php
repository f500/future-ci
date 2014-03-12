<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Command\Command;
use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Task\ResultParser;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

/**
 * Class TaskRunnerSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Runner
 */
class TaskRunnerSpec extends ObjectBehavior
{

    function let(CommandFactory $commandFactory, CommandExecutor $commandExecutor, LoggerInterface $logger)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($commandFactory, $commandExecutor, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Runner\TaskRunner');
    }

    function it_runs_a_task(
        Task $task,
        Build $build,
        Result $result,
        Wrapper $wrapper,
        ResultParser $resultParser,
        Command $commandOne,
        Command $commandTwo,
        CommandExecutor $commandExecutor
    ) {
        $task->getCn()
            ->willReturn('some_task');
        $task->buildCommands(Argument::type('F500\CI\Build\Build'), Argument::type('F500\CI\Command\CommandFactory'))
            ->willReturn(array($commandOne, $commandTwo));
        $task->getWrappers()
            ->willReturn(array('some_wrapper' => $wrapper));
        $task->getResultParsers()
            ->willReturn(array('some_parser' => $resultParser));

        $wrapper->wrap($commandOne, Argument::type('F500\CI\Command\CommandFactory'))->willReturn($commandOne);
        $wrapper->wrap($commandTwo, Argument::type('F500\CI\Command\CommandFactory'))->willReturn($commandTwo);

        $commandExecutor->execute($commandOne, Argument::type('Psr\Log\LoggerInterface'))->willReturn(true);
        $commandExecutor->execute($commandTwo, Argument::type('Psr\Log\LoggerInterface'))->willReturn(true);

        $this->run($task, $build, $result)->shouldReturn(true);

        $wrapper->wrap(Argument::type('F500\CI\Command\Command'), Argument::type('F500\CI\Command\CommandFactory'))
            ->shouldHaveBeenCalled();
        $result->addCommandResult($task, Argument::type('F500\CI\Command\Command'))
            ->shouldHaveBeenCalled();
        $resultParser->parse($task, $result)
            ->shouldHaveBeenCalled();
    }

    function it_fails_to_run_a_task(
        Task $task,
        Build $build,
        Result $result,
        Wrapper $wrapper,
        Command $commandOne,
        Command $commandTwo,
        CommandExecutor $commandExecutor
    ) {
        $task->getCn()->willReturn('some_task');
        $task->buildCommands(Argument::type('F500\CI\Build\Build'), Argument::type('F500\CI\Command\CommandFactory'))
            ->willReturn(array($commandOne, $commandTwo));
        $task->getWrappers()
            ->willReturn(array('some_wrapper' => $wrapper));

        $wrapper->wrap($commandOne, Argument::type('F500\CI\Command\CommandFactory'))->willReturn($commandOne);
        $wrapper->wrap($commandTwo, Argument::type('F500\CI\Command\CommandFactory'))->willReturn($commandTwo);

        $commandExecutor->execute($commandOne, Argument::type('Psr\Log\LoggerInterface'))->willReturn(true);
        $commandExecutor->execute($commandTwo, Argument::type('Psr\Log\LoggerInterface'))->willReturn(false);

        $this->run($task, $build, $result)->shouldReturn(false);
    }
}
