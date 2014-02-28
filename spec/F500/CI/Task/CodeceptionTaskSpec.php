<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;
use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CodeceptionTaskSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class CodeceptionTaskSpec extends TaskSpec
{

    protected $defaultOptions = array(
        'cwd'         => null,
        'environment' => array(),
        'bin'         => '/usr/bin/env codecept',
        'config'      => null,
        'log_dir'     => 'tests/_log',
        'verbose'     => 0,
        'coverage'    => false,
        'suite'       => null,
        'test'        => null,
        'groups'      => array(),
        'envs'        => array(),
        'skip_suites' => array(),
        'skip-groups' => array()
    );

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Task');
    }

    function it_has_a_name()
    {
        $this->setName('Some Task');

        $this->getName()->shouldReturn('Some Task');
    }

    function it_runs_itself(
        Suite $suite,
        Build $build,
        Toolkit $toolkit,
        CommandFactory $commandFactory,
        CommandExecutor $executor,
        Command $command,
        StoreResultCommand $storeResultCommand,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $suite->getProjectDir()->willReturn('/path/to/project');
        $suite->getActiveBuild()->willReturn($build);

        $build->getBuildDir()->willReturn(realpath(__DIR__ . '/../../../data/builds'));

        $toolkit->getCommandFactory()->willReturn($commandFactory);
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

        $commandFactory->createCommand()->willReturn($command);
        $commandFactory->createStoreResultCommand()->willReturn($storeResultCommand);

        $toolkit->getCommandExecutor()->willReturn($executor);

        $executor->execute(Argument::type('F500\CI\Command\Command'), $logger)->willReturn(true);

        $this->run($toolkit)->shouldReturn(true);
    }
}
