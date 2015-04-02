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
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task\Result
 */
class CodeceptionResultParserSpec extends ResultParserSpec
{

    protected $passedReport = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<testsuites>
  <testsuite name="unit" tests="10" assertions="11" failures="1" errors="1" time="0.141928">
    <testcase name="testTypesCanBeRetrievedByName" class="Notification\\ManagerTest" file="/vagrant-poolz-app/tests/unit/Notification/ManagerTest.php" line="62" assertions="2" time="0.005319"/>
  </testsuite>
</testsuites>
XML;


    protected $failedReport = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<testsuites>
  <testsuite name="unit" tests="10" assertions="11" failures="1" errors="1" time="0.141928">
    <testcase name="testTypesCanBeRegistered" class="Notification\\ManagerTest" file="/vagrant-poolz-app/tests/unit/Notification/ManagerTest.php" line="36" assertions="1" time="0.061458">
      <failure type="PHPUnit_Framework_ExpectationFailedException">Notification\\ManagerTest::testTypesCanBeRegistered
Failed asserting that actual size 0 matches expected size 1.

/vagrant-poolz-app/tests/unit/Notification/ManagerTest.php:38
</failure>
    </testcase>
    <testcase name="testTypesCanBeRetrieved" class="Notification\\ManagerTest" file="/vagrant-poolz-app/tests/unit/Notification/ManagerTest.php" line="49" assertions="0" time="0.005097">
      <error type="PHPUnit_Framework_ExceptionWrapper">Notification\\ManagerTest::testTypesCanBeRetrieved
Exception:

</error>
    </testcase>
    <testcase name="testTypesCanBeRetrievedByName" class="Notification\\ManagerTest" file="/vagrant-poolz-app/tests/unit/Notification/ManagerTest.php" line="62" assertions="2" time="0.005319"/>
  </testsuite>
</testsuites>
XML;

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Codeception\CodeceptionResultParser');
        $this->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_determines_if_the_task_has_passed(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn($this->passedReport);

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsPassed($task)->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsPassed($task)->shouldHaveBeenCalled();
    }

    function it_determines_if_the_task_has_failed(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn($this->failedReport);

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsFailed($task, Argument::type('string'))->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsFailed($task, Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_should_fail_on_empty_xml(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn("");

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsBorked($task, Argument::type('string'))->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsBorked($task, Argument::type('string'))->shouldHaveBeenCalled();
    }

    function it_should_fail_if_there_are_no_tests(Task $task, Result $result, Filesystem $filesystem)
    {
        $filesystem->exists(Argument::type('string'))->willReturn(true);
        $filesystem->readFile(Argument::type('string'))->willReturn(<<<XML
<?xml version="1.0"?>
<testsuites/>
XML
);

        $result->getBuildDir($task)->willReturn('/path/to/build');
        $result->getFilesystem()->willReturn($filesystem);
        $result->markTaskAsFailed($task, Argument::type('string'))->willReturn();

        $this->parse($task, $result);

        $result->markTaskAsFailed(
            $task,
            'No tests have been executed, please check your test suite configuration'
        )->shouldHaveBeenCalled();
    }
}
