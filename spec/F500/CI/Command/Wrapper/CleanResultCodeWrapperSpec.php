<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\PhpSpec\Doubler;
use Prophecy\Argument;

/**
 * Class CleanResultCodeWrapperSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Command\Wrapper
 */
class CleanResultCodeWrapperSpec extends WrapperSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\Wrapper\CleanResultCodeWrapper');
        $this->shouldImplement('F500\CI\Command\Wrapper\Wrapper');
    }

    function it_has_default_options()
    {
        $options = array(
            'cwd' => '',
            'env' => array()
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array(
            'cwd' => '/tmp',
            'env' => array(),
            'foo' => 'bar'
        );

        $this->setOptions(
            array(
                'cwd' => '/tmp',
                'foo' => 'bar'
            )
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_can_wrap_a_command_in_a_new_command(
        CommandFactory $commandFactory,
        Command $oldCommand,
        Command $newCommand
    ) {
        $oldCommand->getArgs()->willReturn(array('ls', '-l'));
        $oldCommand->getCwd()->willReturn('/tmp');
        $oldCommand->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));

        Doubler::get()->stubCommand($newCommand, $commandFactory);

        $wrappedCommand = $this->wrap($oldCommand, $commandFactory);

        $wrappedCommand->shouldImplement('F500\CI\Command\Command');
        $wrappedCommand->getArgs()->shouldReturn(
            array(
                'ls',
                '-l',
                '||',
                'echo',
                '"Unclean result code: $?"'
            )
        );
        $wrappedCommand->getCwd()->shouldReturn('/tmp');
        $wrappedCommand->getEnv()->shouldReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
    }
}
