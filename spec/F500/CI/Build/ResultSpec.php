<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Build\Result;
use F500\CI\Command\Command;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ResultSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class ResultSpec extends ObjectBehavior
{

    function let(Task $task, Command $command, Filesystem $filesystem)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($filesystem, '/path/to/build');

        $task->getCn()->willReturn('some_task');

        $command->getId()->willReturn('a1b2c3d4');
        $command->stringify()->willReturn('ls -l');
        $command->getResultCode()->willReturn(0);
        $command->getOutput()->willReturn('Some output...');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\Result');
    }

    function it_has_a_filesystem()
    {
        $this->getFilesystem()->shouldReturnAnInstanceOf('F500\CI\Filesystem\Filesystem');
    }

    function it_has_a_build_dir()
    {
        $this->getBuildDir()->shouldReturn('/path/to/build');
    }

    function it_has_a_build_dir_for_a_task(Task $task)
    {
        $this->getBuildDir($task)->shouldReturn('/path/to/build/some_task');
    }

    function it_has_task_results_after_adding_command_results(Task $task, Command $command)
    {
        $this->addCommandResult($task, $command);

        $this->getTaskResults($task)->shouldReturn(
            array(
                'commands' => array(
                    'a1b2c3d4' => array(
                        'task'        => 'some_task',
                        'command_id'  => 'a1b2c3d4',
                        'command'     => 'ls -l',
                        'result_code' => 0,
                        'output'      => 'Some output...'
                    )
                )
            )
        );
    }

    function it_has_build_results_after_adding_command_results(Task $task, Command $command)
    {
        $this->addCommandResult($task, $command);

        $this->getBuildResults()->shouldReturn(
            array(
                'some_task' => array(
                    'commands' => array(
                        'a1b2c3d4' => array(
                            'task'        => 'some_task',
                            'command_id'  => 'a1b2c3d4',
                            'command'     => 'ls -l',
                            'result_code' => 0,
                            'output'      => 'Some output...'
                        )
                    )
                )
            )
        );
    }

    function it_has_task_results_after_task_is_marked(Task $task)
    {
        $this->markTaskAsPassed($task);

        $this->getTaskResults($task)->shouldReturn(
            array(
                'result' => Result::PASSED
            )
        );
    }

    function it_has_build_results_after_task_is_marked(Task $task)
    {
        $this->markTaskAsPassed($task);

        $this->getBuildResults()->shouldReturn(
            array(
                'some_task' => array(
                    'result' => Result::PASSED
                )
            )
        );
    }

    function it_has_a_borked_task_status_after_task_is_marked_as_borked(Task $task)
    {
        $this->markTaskAsBorked($task);

        $this->getTaskStatus($task)->shouldReturn(Result::BORKED);
    }

    function it_has_a_failed_task_status_after_task_is_marked_as_failed(Task $task)
    {
        $this->markTaskAsFailed($task);

        $this->getTaskStatus($task)->shouldReturn(Result::FAILED);
    }

    function it_has_a_passed_task_status_after_task_is_marked_as_passed(Task $task)
    {
        $this->markTaskAsPassed($task);

        $this->getTaskStatus($task)->shouldReturn(Result::PASSED);
    }

    function it_has_no_task_status_when_task_is_not_marked(Task $task)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getTaskStatus',
            array($task)
        );
    }

    function it_has_a_borked_build_status_when_a_build_has_a_borked_task(
        Task $task1,
        Task $task2,
        Task $task3
    ) {
        $task1->getCn()->willReturn('task_one');
        $task2->getCn()->willReturn('task_two');
        $task3->getCn()->willReturn('task_three');

        $this->markTaskAsPassed($task1);
        $this->markTaskAsBorked($task2);
        $this->markTaskAsPassed($task3);

        $this->getBuildStatus()->shouldReturn(Result::BORKED);
    }

    function it_has_a_failed_build_status_when_a_build_has_a_failed_task(Task $task1, Task $task2, Task $task3)
    {
        $task1->getCn()->willReturn('task_one');
        $task2->getCn()->willReturn('task_two');
        $task3->getCn()->willReturn('task_three');

        $this->markTaskAsPassed($task1);
        $this->markTaskAsPassed($task2);
        $this->markTaskAsFailed($task3);

        $this->getBuildStatus()->shouldReturn(Result::FAILED);
    }

    function it_has_a_passed_build_status_when_a_build_only_has_passed_tasks(
        Task $task1,
        Task $task2,
        Task $task3
    ) {
        $task1->getCn()->willReturn('task_one');
        $task2->getCn()->willReturn('task_two');
        $task3->getCn()->willReturn('task_three');

        $this->markTaskAsPassed($task1);
        $this->markTaskAsPassed($task2);
        $this->markTaskAsPassed($task3);

        $this->getBuildStatus()->shouldReturn(Result::PASSED);
    }

    function it_has_an_elapsed_time_for_a_build()
    {
        $this->setElapsedBuildTime(123456789);

        $this->getElapsedBuildTime()->shouldReturn(123456789);
    }

    function it_has_no_elapsed_time_for_a_build_if_not_set()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getElapsedBuildTime'
        );
    }

    function it_has_an_elapsed_time_for_a_task(Task $task)
    {
        $this->setElapsedTaskTime($task, 123456789);

        $this->getElapsedTaskTime($task)->shouldReturn(123456789);
    }

    function it_has_no_elapsed_time_for_a_task_if_not_set(Task $task)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getElapsedTaskTime',
            array($task)
        );
    }
}
