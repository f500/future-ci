<?php

namespace spec\F500\CI\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use Prophecy\Argument;

class AnsibleWrapperSpec extends WrapperSpec
{

    protected $defaultOptions = array(
        'bin'         => '/usr/bin/ansible',
        'host'        => null,
        'inventory'   => null,
        'limit'       => null,
        'user'        => null,
        'private_key' => null,
        'sudo'        => false,
        'module_path' => null,
        'timeout'     => 10,
        'verbose'     => 0
    );

    function it_can_wrap_a_command_in_a_new_command(CommandFactory $commandFactory, Command $oldCommand, Command $newCommand)
    {
        $oldCommand->getArgs()->willReturn(array('ls', '-l'));
        $oldCommand->getCwd()->willReturn('/tmp');
        $oldCommand->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));

        $this->mock_new_command($commandFactory, $newCommand);

        $this->setOptions(array('host' => 'localhost', 'inventory' => '/etc/ansible/hosts'));

        $wrappedCommand = $this->wrap($oldCommand, $commandFactory);
        $wrappedCommand->shouldImplement('F500\CI\Command\Command');
        $wrappedCommand->getArgs()->shouldReturn(
            array(
                '/usr/bin/ansible',
                'localhost',
                '--inventory-file=/etc/ansible/hosts',
                '--timeout=10',
                '-m',
                'shell',
                '-a',
                'PATH=/usr/local/bin:/usr/bin:/bin ls -l chdir=/tmp'
            )
        );
        $wrappedCommand->getCwd()->shouldReturn(null);
        $wrappedCommand->getEnv()->shouldReturn(array());
    }
}
