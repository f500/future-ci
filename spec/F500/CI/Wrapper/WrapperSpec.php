<?php

namespace spec\F500\CI\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

abstract class WrapperSpec extends ObjectBehavior
{

    protected $defaultOptions = array();

    protected $accumulatedArgs = array();
    protected $accumulatedEnvs = array();

    function let(Suite $suite)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_wrapper', $suite);

        $suite->addWrapper('some_wrapper', $this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Wrapper\AnsibleWrapper');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_wrapper');
    }

    function it_has_a_suite(Suite $suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }

    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn($this->defaultOptions);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array_replace_recursive(
            $this->defaultOptions,
            array(
                'some_option'  => 'foo',
                'other_option' => 'bar'
            )
        );

        $this->setOptions($options);
        $this->getOptions()->shouldReturn($options);
    }

    function mock_new_command(CommandFactory $commandFactory, Command $command)
    {
        $accumulatedArgs = &$this->accumulatedArgs;

        $command->getArgs()->willReturn(array());
        $command->addArg(Argument::type('string'))->will(
            function ($args) use (&$accumulatedArgs) {
                $accumulatedArgs[] = $args[0];
                $this->getArgs()->willReturn($accumulatedArgs);
            }
        );

        $command->getCwd()->willReturn(null);
        $command->setCwd(Argument::type('string'))->will(
            function ($args) {
                $this->getCwd()->willReturn($args[0]);
            }
        );

        $accumulatedEnvs = &$this->accumulatedEnvs;

        $command->getEnv()->willReturn(array());
        $command->addEnv(Argument::type('string'), Argument::type('string'))->will(
            function ($args) use (&$accumulatedEnvs) {
                $accumulatedEnvs[$args[0]] = $args[1];
                $this->getEnv()->willReturn($accumulatedEnvs);
            }
        );

        $commandFactory->create()->willReturn($command);
    }
}
