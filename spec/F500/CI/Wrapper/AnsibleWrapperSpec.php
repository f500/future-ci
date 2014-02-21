<?php

namespace spec\F500\CI\Wrapper;

use F500\CI\Command\Command;
use PhpSpec\ObjectBehavior;
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

    function it_can_wrap_a_command(Command $command)
    {
        $command->getArgs()->willReturn(array('ls -l'));
        $command->getCwd()->willReturn('/tmp');
        $command->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));

        $this->wrap($command)->shouldHaveType('F500\CI\Command\Command');
    }
}
