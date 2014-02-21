<?php

namespace spec\F500\CI\Suite;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SuiteFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Suite\SuiteFactory');
    }

    function it_creates_a_suite()
    {
        $suite = $this->create('F500\CI\Suite\StandardSuite', 'some_suite');
        $suite->shouldHaveType('F500\CI\Suite\StandardSuite');
        $suite->shouldImplement('F500\CI\Suite\Suite');
    }

    function it_fails_to_create_a_suite_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('NonExistent\Suite', 'some_suite')
        );
    }

    function it_fails_to_create_a_suite_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('StdClass', 'some_suite')
        );
    }
}
