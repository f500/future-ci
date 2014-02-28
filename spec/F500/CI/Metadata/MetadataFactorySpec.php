<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Metadata;

use F500\CI\Build\Build;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class MetadataFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Metadata
 */
class MetadataFactorySpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(
            'F500\CI\Metadata\BuildMetadata',
            'F500\CI\Metadata\SuiteMetadata',
            'F500\CI\Metadata\TaskMetadata'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Metadata\MetadataFactory');
    }

    function it_creates_build_metadata(Build $build)
    {
        $metadata = $this->createBuildMetadata($build);
        $metadata->shouldHaveType('F500\CI\Metadata\BuildMetadata');
        $metadata->shouldImplement('F500\CI\Metadata\Metadata');
    }

    function it_fails_to_create_build_metadata_when_class_does_not_exist(Build $build)
    {
        $this->beConstructedWith(
            'NonExistent\BuildMetadata',
            'F500\CI\Metadata\SuiteMetadata',
            'F500\CI\Metadata\TaskMetadata'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createBuildMetadata',
            array($build)
        );
    }

    function it_fails_to_create_build_metadata_when_interface_not_implemented(Build $build)
    {
        $this->beConstructedWith(
            'StdClass',
            'F500\CI\Metadata\SuiteMetadata',
            'F500\CI\Metadata\TaskMetadata'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createBuildMetadata',
            array($build)
        );
    }

    function it_creates_suite_metadata(Suite $suite)
    {
        $metadata = $this->createSuiteMetadata($suite);
        $metadata->shouldHaveType('F500\CI\Metadata\SuiteMetadata');
        $metadata->shouldImplement('F500\CI\Metadata\Metadata');
    }

    function it_fails_to_create_suite_metadata_when_class_does_not_exist(Suite $suite)
    {
        $this->beConstructedWith(
            'F500\CI\Metadata\BuildMetadata',
            'NonExistent\SuiteMetadata',
            'F500\CI\Metadata\TaskMetadata'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createSuiteMetadata',
            array($suite)
        );
    }

    function it_fails_to_create_suite_metadata_when_interface_not_implemented(Suite $suite)
    {
        $this->beConstructedWith(
            'F500\CI\Metadata\BuildMetadata',
            'StdClass',
            'F500\CI\Metadata\TaskMetadata'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createSuiteMetadata',
            array($suite)
        );
    }

    function it_creates_task_metadata(Task $task)
    {
        $metadata = $this->createTaskMetadata($task);
        $metadata->shouldHaveType('F500\CI\Metadata\TaskMetadata');
        $metadata->shouldImplement('F500\CI\Metadata\Metadata');
    }

    function it_fails_to_create_task_metadata_when_class_does_not_exist(Task $task)
    {
        $this->beConstructedWith(
            'F500\CI\Metadata\BuildMetadata',
            'F500\CI\Metadata\SuiteMetadata',
            'NonExistent\TaskMetadata'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createTaskMetadata',
            array($task)
        );
    }

    function it_fails_to_create_task_metadata_when_interface_not_implemented(Task $task)
    {
        $this->beConstructedWith(
            'F500\CI\Metadata\BuildMetadata',
            'F500\CI\Metadata\SuiteMetadata',
            'StdClass'
        );

        $this->shouldThrow('InvalidArgumentException')->during(
            'createTaskMetadata',
            array($task)
        );
    }
}
