<?php

namespace spec\F500\CI\Wrapper;

use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WrapperFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Wrapper\WrapperFactory');
    }

    function it_creates_a_wrapper(Suite $suite)
    {
        $wrapper = $this->create('F500\CI\Wrapper\AnsibleWrapper', 'some_wrapper', $suite);
        $wrapper->shouldHaveType('F500\CI\Wrapper\AnsibleWrapper');
        $wrapper->shouldImplement('F500\CI\Wrapper\Wrapper');
    }

    function it_fails_to_create_a_wrapper_when_class_does_not_exist(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('NonExistent\Wrapper', 'some_wrapper', $suite)
        );
    }

    function it_fails_to_create_a_wrapper_when_interface_not_implemented(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('StdClass', 'some_wrapper', $suite)
        );
    }
}
