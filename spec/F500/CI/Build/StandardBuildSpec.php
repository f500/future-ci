<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class StandardBuildSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class StandardBuildSpec extends ObjectBehavior
{

    function let(Suite $suite, Filesystem $filesystem)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_suite.2014.02.20.09.00.00', $suite, $filesystem);

        $suite->setActiveBuild($this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\StandardBuild');
        $this->shouldImplement('F500\CI\Build\Build');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_suite.2014.02.20.09.00.00');
    }

    function it_has_a_suite(Suite $suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }

    function it_runs_itself(
        Suite $suite,
        Toolkit $toolkit,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $toolkit->getDispatcher()->willReturn($dispatcher);
        $toolkit->getLogger()->willReturn($logger);

        $suite->run($toolkit)->willReturn(true);

        $this->run($toolkit)->shouldReturn(true);
    }
}
