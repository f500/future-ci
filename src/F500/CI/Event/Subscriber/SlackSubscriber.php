<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SlackOutputSubscriber
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event\Subscriber
 */
class SlackSubscriber implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::BuildStarted  => array(
                array('onBuildStarted', 10)
            ),
            Events::BuildFinished => array(
                array('onBuildFinished', 10)
            )
        );
    }

    public function __construct()
    {

    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildStarted(BuildRunEvent $event)
    {
        $build = $event->getBuild();
        $this->slackApi->(
            sprintf(
                'Running build <fg=yellow>%s</fg=yellow> (<fg=yellow>%s</fg=yellow>)',
                $build->getName(),
                $build->getDate()->format('Y-m-d H:i:s')
            )
        );
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildFinished(BuildRunEvent $event)
    {
        $result = $event->getResult();

//        $this->output->writeln(sprintf("\n<fg=%s>%s Build was %s</fg=%s>", $color, $icon, $text, $color));
//        $this->output->writeln(sprintf('(took %s)', $this->stringifyElapsedTime($result->getElapsedBuildTime())));
    }


}
