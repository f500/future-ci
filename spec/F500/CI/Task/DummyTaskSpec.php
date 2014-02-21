<?php

namespace spec\F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DummyTaskSpec extends TaskSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\DummyTask');
        $this->shouldImplement('F500\CI\Task\Task');
    }

    function it_runs_itself(EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $this->mock_dispatcher($dispatcher);
        $this->mock_logger($logger);

        $this->run($dispatcher, $logger)->shouldReturn(true);
    }
}
