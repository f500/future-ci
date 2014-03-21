<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Console\Helper;

use F500\CI\Build\Build;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Runner\BuildRunner;
use F500\CI\Runner\Configurator;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunHelperSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Console\Helper
 */
class RunHelperSpec extends ObjectBehavior
{

    function let(\Pimple $container, Configurator $configurator, BuildRunner $buildRunner, Filesystem $filesystem)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($container);

        $container->offsetGet('f500ci.configurator')->willReturn($configurator);
        $container->offsetGet('f500ci.build_runner')->willReturn($buildRunner);
        $container->offsetGet('filesystem')->willReturn($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Console\Helper\RunHelper');
    }

    function it_can_run_a_build(
        InputInterface $input,
        OutputInterface $output,
        Configurator $configurator,
        BuildRunner $buildRunner,
        Suite $suite,
        Build $build,
        Task $task
    ) {
        $input->getArgument('suite')->willReturn('some_suite.yml');
        $input->getArgument('params')->willReturn(array());

        $configurator->loadConfig(Argument::type('string'), null, Argument::type('array'))
            ->willReturn(
                array(
                    'suite' => array('class' => 'F500\CI\Suite\StandardSuite', 'cn' => 'some_suite'),
                    'build' => array('class' => 'F500\CI\Build\StandardBuild')
                )
            );
        $configurator->createSuite(Argument::type('string'), Argument::type('string'), Argument::type('array'))
            ->willReturn($suite);
        $configurator->createBuild(Argument::type('string'), Argument::type('F500\CI\Suite\Suite'))
            ->willReturn($build);

        $buildRunner->initialize(Argument::type('F500\CI\Build\Build'))
            ->willReturn(true)
            ->shouldBeCalled();
        $buildRunner->run(Argument::type('F500\CI\Build\Build'), Argument::type('F500\CI\Build\Result'))
            ->willReturn(true)
            ->shouldBeCalled();
        $buildRunner->cleanup(Argument::type('F500\CI\Build\Build'), Argument::type('F500\CI\Build\Result'))
            ->willReturn(true)
            ->shouldBeCalled();

        $build->getCn()->willReturn('a1b2c3d4');
        $build->getDate()->willReturn(new \DateTimeImmutable());
        $build->getBuildDir()->willReturn('/path/to/builds/some_suite/a1b2c3d4');
        $build->getSuiteCn()->willReturn('some_suite');
        $build->getSuiteName()->willReturn('Some Suite');
        $build->getTasks()->willReturn(array('some_task' => $task));

        $task->getCn()->willReturn('some_task');
        $task->getName()->willReturn('Some Task');

        $this->execute($input, $output);
    }
}
