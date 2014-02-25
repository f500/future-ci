<?php

namespace spec\F500\CI\Task;

use F500\CI\Run\Toolkit;
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

    function it_runs_itself(Toolkit $toolkit, EventDispatcherInterface $dispatcher, LoggerInterface $logger)
    {
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

//        $this->mock_dispatcher($dispatcher);
//        $this->mock_logger($logger);

        $this->run($toolkit)->shouldReturn(true);
    }
}
