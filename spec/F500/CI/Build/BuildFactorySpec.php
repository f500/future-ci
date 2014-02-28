<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BuildFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class BuildFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\BuildFactory');
    }

    function it_creates_a_build(Suite $suite)
    {
        $build = $this->create('F500\CI\Build\StandardBuild', $suite);
        $build->shouldHaveType('F500\CI\Build\StandardBuild');
        $build->shouldImplement('F500\CI\Build\Build');
    }

    function it_fails_to_create_a_build_when_class_does_not_exist(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('NonExistent\Build', $suite)
        );
    }

    function it_fails_to_create_a_build_when_interface_not_implemented(Suite $suite)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'create',
            array('StdClass', $suite)
        );
    }
}
