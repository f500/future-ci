<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command;

use F500\CI\Process\ProcessFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CommandFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Command
 */
class CommandFactorySpec extends ObjectBehavior
{

    function let(ProcessFactory $processFactory)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('F500\CI\Command\Command', $processFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\CommandFactory');
    }

    function it_creates_a_command()
    {
        $this->create()->shouldHaveType('F500\CI\Command\Command');
    }

    function it_fails_to_create_a_command_when_class_does_not_exist(ProcessFactory $processFactory)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('NonExistent\Command', $processFactory);

        $this->shouldThrow('RuntimeException')->during(
            'create'
        );
    }
}
