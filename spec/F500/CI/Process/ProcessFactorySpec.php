<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Process;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ProcessFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Process
 */
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
