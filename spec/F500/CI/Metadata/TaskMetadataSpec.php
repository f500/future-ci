<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Metadata;

use F500\CI\Task\Task;
use Prophecy\Argument;

/**
 * Class TaskMetadataSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Metadata
 */
class TaskMetadataSpec extends MetadataSpec
{

    function let(Task $task)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($task);

        $task->setMetadata($this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Metadata\TaskMetadata');
        $this->shouldImplement('F500\CI\Metadata\Metadata');
    }
}
