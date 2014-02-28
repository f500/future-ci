<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class StandardBuildSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class StandardBuildSpec extends ObjectBehavior
{

    function let(Suite $suite)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($suite);

        $suite->setActiveBuild($this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\StandardBuild');
        $this->shouldImplement('F500\CI\Build\Build');
    }

    function it_has_a_cn(Suite $suite)
    {
        $suite->getCn()->willReturn('some_suite');

        $this->getCn()->shouldMatch('/^some_suite.\d{4}.\d{2}.\d{2}.\d{2}.\d{2}.\d{2}$/');
    }

    function it_has_a_date()
    {
        $this->getDate()->shouldHaveType('DateTimeImmutable');
    }

    function it_has_a_suite(Suite $suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }

    function it_initializes_itself(Suite $suite, Task $task, Toolkit $toolkit, Filesystem $filesystem, Logger $logger)
    {
        $this->mock_for_initialization($suite, $task, $toolkit, $filesystem, $logger);

        $this->initialize($toolkit)->shouldReturn(true);
    }

    function it_does_not_have_a_build_dir_initially()
    {
        $this->shouldThrow('\RuntimeException')->during(
            'getBuildDir'
        );
    }

    function it_has_a_build_dir_after_initialization(
        Suite $suite,
        Task $task,
        Toolkit $toolkit,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $this->mock_for_initialization($suite, $task, $toolkit, $filesystem, $logger);

        $this->initialize($toolkit);
        $this->getBuildDir()->shouldReturn(
            realpath(__DIR__ . '/../../../data/builds') . '/' . $this->getCn()->getWrappedObject()
        );
    }

    function it_runs_itself(
        Suite $suite,
        Toolkit $toolkit,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

        $suite->getCn()->willReturn('some_suite');
        $suite->run($toolkit)->willReturn(true);

        $this->run($toolkit)->shouldReturn(true);
    }

    function it_cleans_itself_up(Toolkit $toolkit)
    {
        $toolkit->deactivateBuildLogHandler()->shouldBeCalled();

        $this->cleanup($toolkit)->shouldReturn(true);
    }

    protected function mock_for_initialization(
        Suite $suite,
        Task $task,
        Toolkit $toolkit,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $suite->getCn()->willReturn('some_suite');
        $suite->getTasks()->willReturn(array('some_task' => $task));

        $task->getCn()->willReturn('some_task');

        $toolkit->getFilesystem()->willReturn($filesystem);
        $toolkit->getBuildsDir()->willReturn(realpath(__DIR__ . '/../../../data/builds'));
        $toolkit->getLogger()->willReturn($logger);

        $filesystem->mkdir(Argument::type('string'))->shouldBeCalled();
        $filesystem->exists(Argument::type('string'))->willReturn(true);

        $toolkit->activateBuildLogHandler(Argument::type('string'))->shouldBeCalled();
    }
}
