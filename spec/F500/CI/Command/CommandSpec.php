<?php

namespace spec\F500\CI\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\Command');
    }

    function it_has_an_id()
    {
        $this->getId()->shouldBeString();
    }

    function it_can_add_an_argument_to_itself()
    {
        $this->addArg('ls');
        $this->getArgs()->shouldReturn(array('ls'));
    }

    function it_has_a_current_working_directory_after_setting_it()
    {
        $this->setCwd('/tmp');
        $this->getCwd()->shouldReturn('/tmp');
    }

    function it_can_add_an_environment_variable_to_itself()
    {
        $this->addEnv('PATH', '/usr/local/bin:/usr/bin:/bin');
        $this->getEnv()->shouldReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
    }

    function it_stringifies_itself_for_representation()
    {
        $this->addArg('ls');
        $this->addArg('-l');

        $this->stringify()->shouldReturn('ls -l');
    }

    function it_stringifies_itself_for_shorter_representation()
    {
        $this->addArg('echo');
        $this->addArg('Fusce quis pellentesque ipsum. Proin aliquam varius dolor pretium sollicitudin. Donec aliquet tortor eu leo iaculis sed.');

        $this->stringify(true)->shouldReturn('echo Fusce quis pellentesque ipsum. Proin aliquam varius dolor pretium sollic...');
    }
}
