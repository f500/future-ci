<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\Exec;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;
use F500\PhpSpec\Doubler;
use Prophecy\Argument;
use spec\F500\CI\Task\TaskSpec;

/**
 * Class ExecTaskSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class ExecTaskSpec extends TaskSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Exec\ExecTask');
        $this->shouldImplement('F500\CI\Task\Task');
    }

    function it_has_default_options()
    {
        $options = array(
            'cwd'         => '',
            'log_dir'     => '',
            'env'         => array(),
            'bin'         => null,
            'args'        => array(),
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array(
            'cwd'         => '',
            'log_dir'     => '',
            'env'         => array(),
            'bin'         => 'ls',
            'args'        => array('-v'),
            'foo'         => 'bar'
        );

        $newoptions = array(
            'bin'  => 'ls',
            'args' => array('-v'),
            'foo'  => 'bar'
        );

        $this->setOptions($newoptions);
        $this->getOptions()->shouldReturn($options);
    }

    function it_builds_commands(
        Build $build,
        CommandFactory $commandFactory,
        Command $command,
        StoreResultCommand $storeResultCommand
    ) {
        $commandFactory->createCommand()->will(
            function () use ($command) {
                $this->createCommand()->willReturn($command);

                return $command;
            }
        );
        $commandFactory->createStoreResultCommand()->willReturn($storeResultCommand);
        $build->getBuildDir($this->getWrappedObject())->willReturn('/path/to/builds/some_build/some_task');

        Doubler::get()->stubCommand($command);

        $this->setOptions(array('bin' => 'ls', 'args' => array('-lha'), 'log_dir' => '/path/to/project/tests/_log'));

        $commands = $this->buildCommands($build, $commandFactory);
        $commands->shouldBe(array($command, $storeResultCommand));

        $commands[0]->getArgs()->shouldReturn(
            array('ls', '-lha')
        );
        $storeResultCommand
            ->setResultDirs('/path/to/project/tests/_log', '/path/to/builds/some_build/some_task')
            ->shouldHaveBeenCalled();
    }
}
