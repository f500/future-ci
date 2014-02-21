<?php

namespace spec\F500\CI\Process;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProcessFactorySpec extends ObjectBehavior
{

    function let()
    {
        $builderClass = 'Symfony\Component\Process\ProcessBuilder';

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($builderClass);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Process\ProcessFactory');
    }

    function it_creates_a_process()
    {
        $this->create(array('ls'))->shouldHaveType('Symfony\Component\Process\Process');
    }

    function it_fails_to_create_a_process_when_class_does_not_exist()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('NonExistent\ProcessBuilder');

        $this->shouldThrow('RuntimeException')->during(
            'create',
            array(array('ls'))
        );
    }
}
