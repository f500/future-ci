<?php

namespace spec\F500\CI\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandFactorySpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('F500\CI\Command\Command');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\CommandFactory');
    }

    function it_creates_a_command()
    {
        $this->create()->shouldHaveType('F500\CI\Command\Command');
    }

    function it_fails_to_create_a_command_when_class_does_not_exist()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('NonExistent\Command');

        $this->shouldThrow('RuntimeException')->during(
            'create'
        );
    }
}
