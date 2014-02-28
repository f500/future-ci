<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use F500\CI\Event\BuildEvent;
use F500\CI\Event\Events;
use F500\CI\Event\SuiteEvent;
use F500\CI\Event\TaskEvent;
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
    protected $suiteStarted;

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
            Events::BuildFinished => array(
                array('onBuildFinished', 50)
            ),
            Events::SuiteStarted  => array(
                array('onSuiteStarted', 50)
            ),
            Events::SuiteFinished => array(
                array('onSuiteFinished', 50)
            ),
            Events::TaskStarted   => array(
                array('onTaskStarted', 50)
            ),
            Events::TaskFinished  => array(
                array('onTaskFinished', 50)
            )
        );
    }

    /**
     * @param BuildEvent $event
     */
    public function onBuildStarted(BuildEvent $event)
    {
        $this->buildStarted = microtime(true);
    }

    /**
     * @param BuildEvent $event
     */
    public function onBuildFinished(BuildEvent $event)
    {
        $time = round((microtime(true) - $this->buildStarted) * 1000);
        $event->getBuild()->getMetadata()->setElapsedTime($time);
        $this->buildStarted = null;
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteStarted(SuiteEvent $event)
    {
        $this->suiteStarted = microtime(true);
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteFinished(SuiteEvent $event)
    {
        $time = round((microtime(true) - $this->suiteStarted) * 1000);
        $event->getSuite()->getMetadata()->setElapsedTime($time);
        $this->suiteStarted = null;
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskStarted(TaskEvent $event)
    {
        $this->taskStarted = microtime(true);
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskFinished(TaskEvent $event)
    {
        $time = round((microtime(true) - $this->taskStarted) * 1000);
        $event->getTask()->getMetadata()->setElapsedTime($time);
        $this->taskStarted = null;
    }
}
