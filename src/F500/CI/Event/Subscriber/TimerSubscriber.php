<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\Events;
use F500\CI\Event\TaskRunEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TimerSubscriber
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event\Subscriber
 */
class TimerSubscriber implements EventSubscriberInterface
{

    /**
     * @var float
     */
    protected $buildStarted;

    /**
     * @var float
     */
    protected $taskStarted;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::BuildStarted  => array(
                array('onBuildStarted', 50)
            ),
            Events::TaskStarted   => array(
                array('onTaskStarted', 50)
            ),
            Events::TaskFinished  => array(
                array('onTaskFinished', 50)
            ),
            Events::BuildFinished => array(
                array('onBuildFinished', 50)
            )
        );
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildStarted(BuildRunEvent $event)
    {
        $this->buildStarted = microtime(true);
    }

    /**
     * @param TaskRunEvent $event
     */
    public function onTaskStarted(TaskRunEvent $event)
    {
        $this->taskStarted = microtime(true);
    }

    /**
     * @param TaskRunEvent $event
     */
    public function onTaskFinished(TaskRunEvent $event)
    {
        $time = round((microtime(true) - $this->taskStarted) * 1000);
        $event->getResult()->setElapsedTaskTime($event->getTask(), $time);
        $this->taskStarted = null;
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildFinished(BuildRunEvent $event)
    {
        $time = round((microtime(true) - $this->buildStarted) * 1000);
        $event->getResult()->setElapsedBuildTime($time);
        $this->buildStarted = null;
    }
}
