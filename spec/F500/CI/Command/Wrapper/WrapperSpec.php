<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class WrapperSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Wrapper
 */
abstract class WrapperSpec extends ObjectBehavior
{

    protected $accumulatedArgs = array();

    protected $accumulatedEnvs = array();

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_wrapper');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_wrapper');
    }

    function mock_new_command(CommandFactory $commandFactory, Command $command)
    {
        $commandFactory->createCommand()->willReturn($command);

        $accumulatedArgs = & $this->accumulatedArgs;

        $command->getArgs()->willReturn(array());
        $command->addArg(Argument::type('string'))->will(
            function ($args) use (&$accumulatedArgs) {
                $accumulatedArgs[] = $args[0];
                $this->getArgs()->willReturn($accumulatedArgs);
            }
        );

        $command->getCwd()->willReturn();
        $command->setCwd(Argument::type('string'))->will(
            function ($args) {
                $this->getCwd()->willReturn($args[0]);
            }
        );

        $accumulatedEnvs = & $this->accumulatedEnvs;

        $command->getEnv()->willReturn(array());
        $command->addEnv(Argument::type('string'), Argument::type('string'))->will(
            function ($args) use (&$accumulatedEnvs) {
                $accumulatedEnvs[$args[0]] = $args[1];
                $this->getEnv()->willReturn($accumulatedEnvs);
            }
        );
    }

    function mock_new_store_result_command(CommandFactory $commandFactory, StoreResultCommand $command)
    {
        $commandFactory->createStoreResultCommand()->willReturn($command);

        $accumulatedArgs = & $this->accumulatedArgs;

        $command->getArgs()->willReturn(array());
        $command->addArg(Argument::type('string'))->will(
            function ($args) use (&$accumulatedArgs) {
                $accumulatedArgs[] = $args[0];
                $this->getArgs()->willReturn($accumulatedArgs);
            }
        );

        $command->getCwd()->willReturn();
        $command->setCwd(Argument::type('string'))->will(
            function ($args) {
                $this->getCwd()->willReturn($args[0]);
            }
        );

        $accumulatedEnvs = & $this->accumulatedEnvs;

        $command->getEnv()->willReturn(array());
        $command->addEnv(Argument::type('string'), Argument::type('string'))->will(
            function ($args) use (&$accumulatedEnvs) {
                $accumulatedEnvs[$args[0]] = $args[1];
                $this->getEnv()->willReturn($accumulatedEnvs);
            }
        );

        $command->setResultDirs(Argument::type('string'), Argument::type('string'), false)->willReturn();
    }
}
