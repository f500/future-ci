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
            Events::BuildStarted  => array(
                array('onBuildStarted', 10)
            ),
            Events::SuiteStarted  => array(
                array('onSuiteStarted', 10)
            ),
            Events::TaskStarted   => array(
                array('onTaskStarted', 10)
            ),
            Events::TaskFinished  => array(
                array('onTaskFinished', 10)
            ),
            Events::SuiteFinished => array(
                array('onSuiteFinished', 10)
            ),
            Events::BuildFinished => array(
                array('onBuildFinished', 10)
            )
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
     * @param BuildEvent $event
     */
    public function onBuildStarted(BuildEvent $event)
    {
        $build = $event->getBuild();

        $this->output->writeln(
            sprintf(
                "\nRunning build <fg=green>%s</fg=green> (<fg=green>%s</fg=green>):",
                $build->getDate()->format('Y-m-d H:i:s'),
                $build->getSuite()->getCn()
            )
        );
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteStarted(SuiteEvent $event)
    {
        $suite = $event->getSuite();

        $this->output->writeln(
            sprintf(
                "\n<fg=yellow>\xE2\x88\x99</fg=yellow> Suite <fg=yellow>%s</fg=yellow> started.",
                $suite->getName()
            )
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskStarted(TaskEvent $event)
    {
        $task = $event->getTask();

        $this->output->writeln(
            sprintf(
                "\n  <fg=yellow>\xE2\x88\x99</fg=yellow> Task <fg=yellow>%s</fg=yellow> started.",
                $task->getName()
            )
        );
    }

    /**
     * @param TaskEvent $event
     */
    public function onTaskFinished(TaskEvent $event)
    {
        $task = $event->getTask();

        $this->output->writeln('      Took ' . $task->getMetadata()->stringifyElapsedTime());
        $this->output->writeln(
            sprintf(
                "  <fg=yellow>\xE2\x88\x99</fg=yellow> Task <fg=yellow>%s</fg=yellow> finished.",
                $task->getName()
            )
        );
    }

    /**
     * @param SuiteEvent $event
     */
    public function onSuiteFinished(SuiteEvent $event)
    {
        $suite = $event->getSuite();

        $this->output->writeln("\n    Took " . $suite->getMetadata()->stringifyElapsedTime());
        $this->output->writeln(
            sprintf(
                "<fg=yellow>\xE2\x88\x99</fg=yellow> Suite <fg=yellow>%s</fg=yellow> finished.",
                $suite->getName()
            )
        );
    }

    /**
     * @param BuildEvent $event
     */
    public function onBuildFinished(BuildEvent $event)
    {
        $build = $event->getBuild();

        $this->output->writeln(
            sprintf("\nBuild took %s", $build->getMetadata()->stringifyElapsedTime())
        );
    }
}
