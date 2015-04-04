<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Event\Subscriber;

use F500\CI\Build\Build;
use F500\CI\Event\BuildRunEvent;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class TimerSubscriberSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Event\Subscriber
 */
class GitSubscriberSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Event\Subscriber\GitSubscriber');
        $this->shouldImplement('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_registers_the_commit_with_the_build(BuildRunEvent $event, Build $build)
    {
        $event->getBuild()->willReturn($build);
        $build->initiatedBy(Argument::type('F500\CI\Vcs\Commit'))->willReturn(null);

        // Minor evil: because the 'load' method is a static factory method we cannot mock the actual
        // creation of the Commit object; this means that we are going to assume that the current folder
        // is under Version Control using git.
        //
        // It might be better to create a Commit Factory that uses a strategy pattern
        $build->getProjectDir()->willReturn(__DIR__);

        $this->onBuildStarted($event);

        $build->getProjectDir()->shouldHaveBeenCalled();
        $build->initiatedBy(Argument::type('F500\CI\Vcs\Commit'))->shouldHaveBeenCalled();
    }
}
