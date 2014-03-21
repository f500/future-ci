<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command;

use F500\CI\Command\Command;
use F500\CI\Command\Process\ProcessFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

/**
 * Class CommandExecutorSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Command
 */
class CommandExecutorSpec extends ObjectBehavior
{

    function let(ProcessFactory $processFactory)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($processFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\CommandExecutor');
    }

    function it_can_execute_a_command_which_succeeds(
        Command $command,
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_for_execution($command, $logger, $processFactory, $process);

        $process->getExitCode()->willReturn(0);
        $process->getOutput()->willReturn('Some output...');
        $process->getErrorOutput()->willReturn('');
        $process->isSuccessful()->willReturn(true);

        $this->execute($command, $logger)->shouldReturn(true);
    }

    function it_can_execute_a_command_which_fails(
        Command $command,
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_for_execution($command, $logger, $processFactory, $process);

        $process->getExitCode()->willReturn(1);
        $process->getOutput()->willReturn('');
        $process->getErrorOutput()->willReturn('Some output...');
        $process->isSuccessful()->willReturn(false);

        $this->execute($command, $logger)->shouldReturn(false);
    }

    protected function mock_for_execution(
        Command $command,
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $command->getId()->willReturn('a1b2c3d4');
        $command->getArgs()->willReturn(array('ls', '-l'));
        $command->getCwd()->willReturn('/tmp');
        $command->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
        $command->stringify(true)->willReturn('ls -l');
        $command->clearResult()->willReturn();
        $command->setResult(Argument::type('int'), Argument::type('string'))->willReturn();

        $logger->log(Argument::type('string'), Argument::type('string'))->willReturn(true);
        $logger->log(Argument::type('string'), Argument::type('string'), Argument::type('array'))->willReturn(true);

        $processFactory->createProcess(Argument::type('array'), Argument::type('string'), Argument::type('array'))
            ->willReturn($process);

        $process->run()->willReturn();
        $process->getCommandLine()->willReturn('ls -l');
    }
}
