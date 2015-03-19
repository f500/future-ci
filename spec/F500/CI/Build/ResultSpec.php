<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Build\Build;
use F500\CI\Build\Result;
use F500\CI\Command\Command;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ResultSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class ResultSpec extends ObjectBehavior
{

    function let(Build $build, Task $task, Command $command, Filesystem $filesystem, \DateTimeImmutable $date)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($build, $filesystem);

        $date->format('c')->willReturn('1970-01-01T00:00:00+00:00');

        $build->getCn()->willReturn('a1b2c3d4');
        $build->getDate()->willReturn($date);
        $build->getBuildDir()->willReturn('/path/to/builds/some_suite/a1b2c3d4');
        $build->getSuiteCn()->willReturn('some_suite');
        $build->getSuiteName()->willReturn('Some Suite');
        $build->getTasks()->willReturn(array('some_task' => $task));

        $task->getCn()->willReturn('some_task');
        $task->getName()->willReturn('Some Task');

        $command->getId()->willReturn('a1b2c3d4');
        $command->stringify()->willReturn('ls -l');
        $command->getResultCode()->willReturn(0);
        $command->getOutput()->willReturn('Some output...');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\Result');
    }

    function it_has_a_build_dir()
    {
        $this->getBuildDir()->shouldReturn('/path/to/builds/some_suite/a1b2c3d4');
    }

    function it_has_a_build_dir_for_a_task(Task $task)
    {
        $this->getBuildDir($task)->shouldReturn('/path/to/builds/some_suite/a1b2c3d4/some_task');
    }

    function it_has_no_task_results_when_not_added(Task $task)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getResults',
            array($task)
        );
    }

    function it_has_task_results_after_adding_a_command_result(Task $task, Command $command)
    {
        $this->addCommandResult($task, $command);

        $this->getResults($task)->shouldReturn(
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

    function it_has_task_results_after_adding_an_additional_result(Task $task)
    {
        $this->addAdditionalResult($task, 'foo', 'bar');
    }

    function it_has_no_task_status_when_task_is_not_marked(Task $task)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getTaskStatus',
            array($task)
        );
    }

    function it_has_a_passed_task_status_after_task_is_marked_as_passed(Task $task)
    {
        $this->markTaskAsPassed($task);

        $this->getTaskStatus($task)->shouldReturn(Result::PASSED);
    }

    function it_has_a_failed_task_status_after_task_is_marked_as_failed(Task $task)
    {
        $this->markTaskAsFailed($task);

        $this->getTaskStatus($task)->shouldReturn(Result::FAILED);
    }

    function it_has_a_borked_task_status_after_task_is_marked_as_borked(Task $task)
    {
        $this->markTaskAsBorked($task);

        $this->getTaskStatus($task)->shouldReturn(Result::BORKED);
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
        $this->setElapsedBuildTime(12345678);

        $this->getElapsedBuildTime()->shouldReturn(12345678);
    }

    function it_has_no_elapsed_time_for_a_build_if_not_set()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getElapsedBuildTime'
        );
    }

    function it_has_an_elapsed_time_for_a_task(Task $task)
    {
        $this->setElapsedTaskTime($task, 12345678);

        $this->getElapsedTaskTime($task)->shouldReturn(12345678);
    }

    function it_has_no_elapsed_time_for_a_task_if_not_set(Task $task)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getElapsedTaskTime',
            array($task)
        );
    }

    function it_turns_itself_into_json(Task $task, Command $command)
    {
        $json = <<< EOT
{
    "metadata": {
        "suite": {
            "cn": "some_suite",
            "name": "Some Suite"
        },
        "build": {
            "cn": "a1b2c3d4",
            "date": "1970-01-01T00:00:00+00:00",
            "dir": "\/path\/to\/builds\/some_suite\/a1b2c3d4",
            "time": 12345678
        },
        "tasks": {
            "some_task": {
                "cn": "some_task",
                "name": "Some Task",
                "time": 12345678
            }
        }
    },
    "statuses": {
        "build": "passed",
        "tasks": {
            "some_task": "passed"
        }
    },
    "results": {
        "some_task": {
            "commands": {
                "a1b2c3d4": {
                    "task": "some_task",
                    "command_id": "a1b2c3d4",
                    "command": "ls -l",
                    "result_code": 0,
                    "output": "Some output..."
                }
            },
            "message": ""
        }
    }
}
EOT;

        $this->addCommandResult($task, $command);
        $this->markTaskAsPassed($task);
        $this->setElapsedTaskTime($task, 12345678);
        $this->setElapsedBuildTime(12345678);

        $this->toJson()->shouldReturn($json);
    }

    function it_has_a_filesystem()
    {
        $this->getFilesystem()->shouldReturnAnInstanceOf('F500\CI\Filesystem\Filesystem');
    }
}
