<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Suite;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class SuiteFactorySpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Suite
 */
class SuiteFactorySpec extends ObjectBehavior
{

    protected $config = array(
        'suite' => array(
            'name'  => 'Some Suite',
            'cn'    => 'some_suite',
            'class' => 'F500\CI\Suite\StandardSuite'
        ),
        'build' => array(
            'class' => 'F500\CI\Build\StandardBuild'
        )
    );

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Suite\SuiteFactory');
    }

    function it_creates_a_suite()
    {
        $suite = $this->createSuite('F500\CI\Suite\StandardSuite', 'some_suite', $this->config);

        $suite->shouldHaveType('F500\CI\Suite\StandardSuite');
        $suite->shouldImplement('F500\CI\Suite\Suite');
    }

    function it_fails_to_create_a_suite_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createSuite',
            array('NonExistent\Suite', 'some_suite', $this->config)
        );
    }

    function it_fails_to_create_a_suite_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createSuite',
            array('StdClass', 'some_suite', $this->config)
        );
    }
}
