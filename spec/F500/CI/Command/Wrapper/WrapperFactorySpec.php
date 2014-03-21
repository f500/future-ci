<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command\Wrapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class WrapperFactorySpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Wrapper
 */
class WrapperFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\Wrapper\WrapperFactory');
    }

    function it_creates_a_wrapper()
    {
        $wrapper = $this->createWrapper('F500\CI\Command\Wrapper\AnsibleWrapper', 'some_wrapper');
        $wrapper->shouldHaveType('F500\CI\Command\Wrapper\AnsibleWrapper');
        $wrapper->shouldImplement('F500\CI\Command\Wrapper\Wrapper');
    }

    function it_fails_to_create_a_wrapper_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createWrapper',
            array('NonExistent\Wrapper', 'some_wrapper')
        );
    }

    function it_fails_to_create_a_wrapper_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createWrapper',
            array('StdClass', 'some_wrapper')
        );
    }
}
