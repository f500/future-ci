<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Run;

use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ToolkitSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Run
 */
class ToolkitSpec extends ObjectBehavior
{

    function let(
        CommandFactory $commandFactory,
        CommandExecutor $commandExecutor,
        EventDispatcherInterface $dispatcher,
        Filesystem $filesystem,
        Logger $logger
    ) {
        $buildsDir = __DIR__ . '/../../../data/builds';

        $this->beConstructedWith($buildsDir, $commandFactory, $commandExecutor, $dispatcher, $filesystem, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Run\Toolkit');
    }

    function it_has_a_builds_dir()
    {
        $this->getBuildsDir()->shouldReturn(realpath(__DIR__ . '/../../../data/builds'));
    }

    function it_has_a_command_factory()
    {
        $this->getCommandFactory()->shouldHaveType('F500\CI\Command\CommandFactory');
    }

    function it_has_a_command_executor()
    {
        $this->getCommandExecutor()->shouldHaveType('F500\CI\Command\CommandExecutor');
    }

    function it_has_an_event_dispatcher()
    {
        $this->getDispatcher()->shouldImplement('Symfony\Component\EventDispatcher\EventDispatcherInterface');
    }

    function it_has_a_filesystem()
    {
        $this->getFilesystem()->shouldHaveType('Symfony\Component\Filesystem\Filesystem');
    }

    function it_has_a_logger()
    {
        $this->getLogger()->shouldHaveType('Monolog\Logger');
    }

    function it_activates_a_build_log_handler(Logger $logger)
    {
        $logfile = realpath(__DIR__ . '/../../../data/builds') . '/some_suite.2014.02.20.09.00.00/build.log';

        $logger->pushHandler(Argument::type('Monolog\Handler\StreamHandler'))->shouldBeCalled();

        $this->activateBuildLogHandler($logfile);
    }

    function it_cannot_activate_a_build_log_handler_if_already_activated()
    {
        $logfile = realpath(__DIR__ . '/../../../data/builds') . '/some_suite.2014.02.20.09.00.00/build.log';

        $this->activateBuildLogHandler($logfile);

        $this->shouldThrow('\RuntimeException')->during(
            'activateBuildLogHandler',
            array($logfile)
        );
    }

    function it_deactivates_a_build_log_handler(Logger $logger)
    {
        $logfile = realpath(__DIR__ . '/../../../data/builds') . '/some_suite.2014.02.20.09.00.00/build.log';

        $logger->pushHandler(Argument::type('Monolog\Handler\StreamHandler'))->shouldBeCalled();
        $logger->popHandler()->shouldBeCalled();

        $this->activateBuildLogHandler($logfile);
        $this->deactivateBuildLogHandler();
    }

    function it_cannot_deactivate_a_build_log_handler_if_not_activated()
    {
        $this->shouldThrow('\RuntimeException')->during(
            'deactivateBuildLogHandler'
        );
    }
}
