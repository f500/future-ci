<?php

namespace spec\F500\CI\Command;

use F500\CI\Process\ProcessFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class CommandSpec extends ObjectBehavior
{

    function let(ProcessFactory $processFactory)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($processFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\Command');
    }

    function it_has_an_id()
    {
        $this->getId()->shouldBeString();
    }

    function it_can_add_an_argument_to_itself()
    {
        $this->addArg('ls');
        $this->getArgs()->shouldReturn(array('ls'));
    }

    function it_has_a_current_working_directory_after_setting_it()
    {
        $this->setCwd('/tmp');
        $this->getCwd()->shouldReturn('/tmp');
    }

    function it_can_add_an_environment_variable_to_itself()
    {
        $this->addEnv('PATH', '/usr/local/bin:/usr/bin:/bin');
        $this->getEnv()->shouldReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
    }

    function it_stringifies_itself_for_representation()
    {
        $this->addArg('ls');
        $this->addArg('-l');

        $this->stringify()->shouldReturn('ls -l');
    }

    function it_stringifies_itself_for_shorter_representation()
    {
        $this->addArg('echo');
        $this->addArg(
            'Fusce quis pellentesque ipsum. Proin aliquam varius dolor pretium sollicitudin. Donec aliquet tortor eu leo iaculis sed.'
        );

        $this->stringify(true)->shouldReturn(
            'echo Fusce quis pellentesque ipsum. Proin aliquam varius dolor pretium sollic...'
        );
    }

    function it_executes_itself(LoggerInterface $logger, ProcessFactory $processFactory, Process $process)
    {
        $this->mock_successful_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(true);
    }

    function it_has_no_result_code_initially()
    {
        $this->getResultCode()->shouldReturn(null);
    }

    function it_has_result_code_zero_after_execute_succeeded(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_successful_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(true);
        $this->getResultCode()->shouldReturn(0);
    }

    function it_has_result_code_nonzero_after_execute_failed(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_failed_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(false);
        $this->getResultCode()->shouldReturn(1);
    }

    function it_has_no_output_initially()
    {
        $this->getOutput()->shouldReturn(null);
    }

    function it_has_output_after_execute_succeeded(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_successful_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(true);
        $this->getOutput()->shouldReturn(array('Successful output...'));
    }

    function it_has_no_output_after_execute_failed(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_failed_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(false);
        $this->getOutput()->shouldReturn(array());
    }

    function it_has_no_error_output_initially()
    {
        $this->getErrorOutput()->shouldReturn(null);
    }

    function it_has_no_error_output_after_execute_succeeded(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_successful_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(true);
        $this->getErrorOutput()->shouldReturn(array());
    }

    function it_has_error_output_after_execute_failed(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $this->mock_failed_execution($logger, $processFactory, $process);

        $this->execute($logger)->shouldReturn(false);
        $this->getErrorOutput()->shouldReturn(array('Failed output...'));
    }

    protected function mock_successful_execution(
        LoggerInterface $logger,
        ProcessFactory $processFactory,
        Process $process
    ) {
        $logger->log(Argument::type('string'), Argument::type('string'))->willReturn(true);
        $logger->log(Argument::type('string'), Argument::type('string'), Argument::type('array'))->willReturn(true);

        $processFactory->create(Argument::type('array'), null, Argument::type('array'))->willReturn($process);

        $process->run()->shouldBeCalled();
        $process->getCommandLine()->willReturn("'ls' '-l'");
        $process->getExitCode()->willReturn(0);
        $process->getOutput()->willReturn('Successful output...');
        $process->getErrorOutput()->willReturn('');
        $process->isSuccessful()->willReturn(true);
    }

    protected function mock_failed_execution(LoggerInterface $logger, ProcessFactory $processFactory, Process $process)
    {
        $logger->log(Argument::type('string'), Argument::type('string'))->willReturn(true);
        $logger->log(Argument::type('string'), Argument::type('string'), Argument::type('array'))->willReturn(true);

        $processFactory->create(Argument::type('array'), null, Argument::type('array'))->willReturn($process);

        $process->run()->shouldBeCalled();
        $process->getCommandLine()->willReturn("'ls' '-l'");
        $process->getExitCode()->willReturn(1);
        $process->getOutput()->willReturn('');
        $process->getErrorOutput()->willReturn('Failed output...');
        $process->isSuccessful()->willReturn(false);
    }
}
