<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use F500\CI\Event\Events;
use F500\CI\Event\SuiteEvent;
use F500\CI\Event\TaskEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConsoleOutputSubscriber
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event\Subscriber
 */
class ConsoleOutputSubscriber implements EventSubscriberInterface
{

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::SuiteStarted  => array('onSuiteStarted'),
            Events::SuiteFinished => array('onSuiteFinished'),
            Events::TaskStarted   => array('onTaskStarted'),
            Events::TaskFinished  => array('onTaskFinished')
        );
    }

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteStarted(SuiteEvent $event)
    {
        $this->output->writeln(
            sprintf(
                "<fg=yellow>\xE2\x88\x99</fg=yellow> Suite <fg=yellow>%s</fg=yellow> started.",
                $event->getSuite()->getName()
            )
        );
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteFinished(SuiteEvent $event)
    {
        $this->output->writeln(
            sprintf(
                "<fg=yellow>\xE2\x88\x99</fg=yellow> Suite <fg=yellow>%s</fg=yellow> finished.",
                $event->getSuite()->getName()
            )
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskStarted(TaskEvent $event)
    {
        $this->output->writeln(
            sprintf(
                "  <fg=yellow>\xE2\x88\x99</fg=yellow> Task <fg=yellow>%s</fg=yellow> started.",
                $event->getTask()->getName()
            )
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskFinished(TaskEvent $event)
    {
        $this->output->writeln(
            sprintf(
                "  <fg=yellow>\xE2\x88\x99</fg=yellow> Task <fg=yellow>%s</fg=yellow> finished.",
                $event->getTask()->getName()
            )
        );
    }
}
