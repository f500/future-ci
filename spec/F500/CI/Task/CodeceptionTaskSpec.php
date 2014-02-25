<?php

namespace spec\F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Run\Toolkit;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CodeceptionTaskSpec extends TaskSpec
{

    protected $defaultOptions = array(
        'cwd' => null,
        'environment' => array(),
        'bin' => '/usr/bin/env codecept',
        'config' => null,
        'verbose' => 0,
        'coverage' => false,
        'suite' => null,
        'test' => null,
        'groups' => array(),
        'envs' => array(),
        'skip_suites' => array(),
        'skip-groups' => array()
    );

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\Task');
    }

    function it_has_a_name()
    {
        $this->setName('Some Task');

        $this->getName()->shouldReturn('Some Task');
    }

    function it_runs_itself(
        Toolkit $toolkit,
        CommandFactory $commandFactory,
        Command $command,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $toolkit->getCommandFactory()->willReturn($commandFactory);
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

        $commandFactory->create()->willReturn($command);

        $command->addArg(Argument::type('string'))->shouldBeCalled();
        $command->execute($logger)->willReturn(true);

        $this->run($toolkit)->shouldReturn(true);
    }
}
