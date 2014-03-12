<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\Codeception;

use F500\CI\Build\Result;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\Task;
use Prophecy\Argument;
use spec\F500\CI\Task\ResultParserSpec;

/**
 * Class CodeceptionResultParserSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task\Result
 */
class CodeceptionResultParserSpec extends ResultParserSpec
{

    protected $successfulReport = <<<'EOT'
{
    "event": "suiteStart",
    "suite": "acceptance",
    "tests": 1
}{
    "event": "testStart",
    "suite": "acceptance",
    "test": "some_test (SomeTest.php)"
}{
    "event": "test",
    "suite": "acceptance",
    "test": "some_test (SomeTest.php)",
    "status": "pass",
    "time": 1.2345678909876,
    "trace": [

    ],
    "message": "",
    "output": ""
}
EOT;

    protected $unsuccessfulReport = <<<'EOT'
{
    "event": "suiteStart",
    "suite": "acceptance",
    "tests": 1
}{
    "event": "testStart",
    "suite": "acceptance",
    "test": "some_test (SomeTest.php)"
}{
    "event": "test",
    "suite": "acceptance",
    "test": "some_test (SomeTest.php)",
    "status": "fail",
    "time": 1.2345678909876,
    "trace": [

    ],
    "message": "",
    "output": ""
}
EOT;

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Codeception\CodeceptionResultParser');
        $this->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_determines_if_a_result_is_successful(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn($this->successfulReport);
        $filesystem->dumpFile(Argument::type('string'), Argument::type('string'))->willReturn();

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsSuccessful($task)->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsSuccessful($task)->shouldHaveBeenCalled();
    }

    function it_determines_if_a_result_is_unsuccessful(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn($this->unsuccessfulReport);
        $filesystem->dumpFile(Argument::type('string'), Argument::type('string'))->willReturn();

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsFailed($task)->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsFailed($task)->shouldHaveBeenCalled();
    }
}
