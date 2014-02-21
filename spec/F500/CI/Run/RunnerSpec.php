<?php

namespace spec\F500\CI\Run;

use F500\CI\Build\Build;
use F500\CI\Run\Configurator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RunnerSpec extends ObjectBehavior
{

    protected $blankConfig = array(
        'name'        => 'Blank Suite',
        'suite_class' => 'F500\CI\Suite\StandardSuite',
        'build_class' => 'F500\CI\Build\StandardBuild'
    );

    function let(
        Configurator $configurator,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $suitesDir = __DIR__ . '/../../../data/suites';
        $buildsDir = __DIR__ . '/../../../data/builds';

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($suitesDir, $buildsDir, $configurator, $dispatcher, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Run\Runner');
    }

    function it_sets_up_a_build(Configurator $configurator, Build $build)
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite.yml';

        $configurator->loadConfig(Argument::type('string'))->willReturn(array());
        $configurator->setup(Argument::type('string'), Argument::type('array'))->willReturn($build);

        $this->setup($filename)->shouldReturn($build);
    }

    function it_initializes_a_build(Build $build)
    {
        $dispatcherArg = Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $loggerArg     = Argument::type('Psr\Log\LoggerInterface');

        $build->initialize($dispatcherArg, $loggerArg)->willReturn(true);

        $this->initialize($build)->shouldReturn(true);
    }

    function it_runs_a_build(Build $build)
    {
        $dispatcherArg = Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $loggerArg     = Argument::type('Psr\Log\LoggerInterface');

        $build->run($dispatcherArg, $loggerArg)->willReturn(true);

        $this->run($build)->shouldReturn(true);
    }

    function it_cleans_up_a_build(Build $build)
    {
        $dispatcherArg = Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $loggerArg     = Argument::type('Psr\Log\LoggerInterface');

        $build->cleanup($dispatcherArg, $loggerArg)->willReturn(true);

        $this->cleanup($build)->shouldReturn(true);
    }
}
