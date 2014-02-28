<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Wrapper;

use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class WrapperFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Wrapper
 */
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
