<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\Codeception;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;
use Prophecy\Argument;
use spec\F500\CI\Task\TaskSpec;

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

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Codeception\CodeceptionTask');
        $this->shouldImplement('F500\CI\Task\Task');
    }

    function it_has_a_name()
    {
        $this->setName('Some Task');

        $this->getName()->shouldReturn('Some Task');
    }

    function it_has_default_options()
    {
        $options = array(
            'cwd'         => '',
            'env'         => array(),
            'bin'         => '/usr/bin/env codecept',
            'config'      => '',
            'log_dir'     => 'tests/_log',
            'verbose'     => 0,
            'coverage'    => false,
            'suite'       => '',
            'test'        => '',
            'groups'      => array(),
            'envs'        => array(),
            'skip_suites' => array(),
            'skip_groups' => array()
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array(
            'cwd'         => '',
            'env'         => array(),
            'bin'         => '/usr/bin/env codecept',
            'config'      => '',
            'log_dir'     => 'tests/_log',
            'verbose'     => 0,
            'coverage'    => false,
            'suite'       => 'foo',
            'test'        => '',
            'groups'      => array(),
            'envs'        => array(),
            'skip_suites' => array('bar', 'baz'),
            'skip_groups' => array(),
            'foo'         => 'bar'
        );

        $newoptions = array(
            'suite'       => 'foo',
            'skip_suites' => array('bar', 'baz'),
            'foo'         => 'bar'
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
        $commandFactory->createCommand()->willReturn($command);
        $commandFactory->createStoreResultCommand()->willReturn($storeResultCommand);

        $this->buildCommands($build, $commandFactory)->shouldReturn(
            array($command, $command, $storeResultCommand)
        );

        $build->getBuildDir($this->getWrappedObject())->shouldHaveBeenCalled();
    }
}
