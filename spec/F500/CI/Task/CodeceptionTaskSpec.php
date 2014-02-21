<?php

namespace spec\F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Process\Process;

class CodeceptionTaskSpec extends TaskSpec
{

    protected $defaultOptions = array(
        'cwd'         => null,
        'environment' => array(),
        'bin'         => '/usr/bin/env codecept',
        'config'      => null,
        'verbose'     => 0,
        'env'         => array()
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
        CommandFactory $commandFactory,
        Command $command,
        ProcessFactory $processFactory,
        Process $process,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $this->mock_command($commandFactory, $command);
        $this->mock_process($processFactory, $process);
        $this->mock_dispatcher($dispatcher);
        $this->mock_logger($logger);

        $this->run($dispatcher, $logger)->shouldReturn(true);
    }
}
