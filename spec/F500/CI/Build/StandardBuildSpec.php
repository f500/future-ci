<?php

namespace spec\F500\CI\Build;

use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StandardBuildSpec extends ObjectBehavior
{

    function let(Suite $suite)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_suite.2014.02.20.09.00.00', $suite);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\StandardBuild');
        $this->shouldImplement('F500\CI\Build\Build');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_suite.2014.02.20.09.00.00');
    }

    function it_runs_itself(Suite $suite, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $suite->run($dispatcher, $logger)
            ->willReturn(true)
            ->shouldBeCalled();

        $dispatcher->dispatch(Argument::type('string'), Argument::type('Symfony\Component\EventDispatcher\Event'))
            ->will(
                function ($args) {
                    return $args[1];
                }
            )
            ->shouldBeCalled();

        $logger->log(Argument::type('string'), Argument::type('string'))
            ->willReturn(true)
            ->shouldBeCalled();

        $this->run($dispatcher, $logger)->shouldReturn(true);
    }
}
