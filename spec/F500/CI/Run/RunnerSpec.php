<?php

namespace spec\F500\CI\Run;

use F500\CI\Build\Build;
use F500\CI\Run\Configurator;
use F500\CI\Run\Toolkit;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RunnerSpec extends ObjectBehavior
{

    protected $blankConfig = array(
        'name'        => 'Blank Suite',
        'suite_class' => 'F500\CI\Suite\StandardSuite',
        'build_class' => 'F500\CI\Build\StandardBuild'
    );

    function let(
        Configurator $configurator,
        Toolkit $toolkit
    ) {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($configurator, $toolkit);
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

    function it_initializes_a_build(Build $build, Toolkit $toolkit)
    {
        $build->initialize($toolkit)->willReturn(true);

        $this->initialize($build, $toolkit)->shouldReturn(true);
    }

    function it_runs_a_build(Build $build, Toolkit $toolkit)
    {
        $build->run($toolkit)->willReturn(true);

        $this->run($build, $toolkit)->shouldReturn(true);
    }

    function it_cleans_up_a_build(Build $build, Toolkit $toolkit)
    {
        $build->cleanup($toolkit)->willReturn(true);

        $this->cleanup($build, $toolkit)->shouldReturn(true);
    }
}
