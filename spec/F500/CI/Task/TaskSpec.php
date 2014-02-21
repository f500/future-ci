<?php

namespace spec\F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use F500\CI\Wrapper\Wrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Process\Process;

abstract class TaskSpec extends ObjectBehavior
{

    protected $defaultOptions = array();

    function let(CommandFactory $commandFactory, Command $command, ProcessFactory $processFactory)
    {
        $commandFactory->create(Argument::type('array'), null, array())->willReturn($command);

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_task', $commandFactory, $processFactory);
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_task');
    }

    function it_has_a_name_after_setting_it()
    {
        $name = 'Some Task';

        $this->setName($name);
        $this->getName()->shouldReturn($name);
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

    function it_can_add_a_wrapper_to_itself(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);
        $this->getWrappers()->shouldReturn(array('some_wrapper' => $wrapper));
    }

    function it_fails_adding_a_wrapper_when_cn_already_exists(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);

        $this->shouldThrow('InvalidArgumentException')->during(
            'addWrapper',
            array('some_wrapper', $wrapper)
        );
    }

    protected function mock_command(CommandFactory $commandFactory, Command $command)
    {
        $commandFactory->create()->willReturn($command);

        $command->getId()->willReturn('ab1cd2ef3');

        $command->getArgs()->willReturn(array('ls', '-l'));
        $command->addArg(Argument::type('string'))->willReturn(null);

        $command->getCwd()->willReturn('/tmp');
        $command->setCwd(Argument::type('string'))->willReturn(null);

        $command->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
        $command->addEnv(Argument::type('string'), Argument::type('string'))->willReturn(null);

        $command->stringify(true)->willReturn('ls -l');
    }

    protected function mock_process(ProcessFactory $processFactory, Process $process)
    {
        $processFactory->create(Argument::type('array'), Argument::any(), Argument::type('array'))
            ->willReturn($process);

        $process->run()->shouldBeCalled();
        $process->getCommandLine()->willReturn("'ls' '-l'");
        $process->isSuccessful()->willReturn(true);
    }

    protected function mock_dispatcher(EventDispatcherInterface $dispatcher)
    {
        $dispatcher->dispatch(Argument::type('string'), Argument::type('Symfony\Component\EventDispatcher\Event'))
            ->will(
                function ($args) {
                    return $args[1];
                }
            )
            ->shouldBeCalled();
    }

    protected function mock_logger(LoggerInterface $logger)
    {
        $logger->log(Argument::type('string'), Argument::type('string'))
            ->willReturn(true)
            ->shouldBeCalled();
    }
}
