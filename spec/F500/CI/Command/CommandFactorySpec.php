<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CommandFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Command
 */
class CommandFactorySpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(
            'F500\CI\Command\Command',
            'F500\CI\Command\StoreResultCommand'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\CommandFactory');
    }

    function it_creates_a_command()
    {
        $this->createCommand()->shouldHaveType('F500\CI\Command\Command');
    }

    function it_fails_to_create_a_command_when_class_does_not_exist()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(
            'NonExistent\Command',
            'F500\CI\Command\StoreResultCommand',
            'Symfony\Component\Process\Process'
        );

        $this->shouldThrow('RuntimeException')->during(
            'createCommand'
        );
    }

    function it_creates_a_store_result_command()
    {
        $this->createStoreResultCommand()->shouldHaveType('F500\CI\Command\StoreResultCommand');
    }

    function it_fails_to_create_a_store_result_command_when_class_does_not_exist()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(
            'F500\CI\Command\Command',
            'NonExistent\StoreResultCommand',
            'Symfony\Component\Process\Process'
        );

        $this->shouldThrow('RuntimeException')->during(
            'createStoreResultCommand'
        );
    }
}
