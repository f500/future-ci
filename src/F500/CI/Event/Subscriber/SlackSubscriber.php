<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use Crummy\Phlack\Phlack;
use F500\CI\Build\Result;
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
     * @var Phlack
     */
    protected $phlack;

    /**
     * @var array
     */
    protected $resultColorMap = array(
        Result::PASSED => 'good',
        Result::FAILED => 'warning',
        Result::BORKED => 'danger'
    );

    /**
     * @var array
     */
    protected $resultTextMap = array(
        Result::PASSED => 'Passed',
        Result::FAILED => 'Failed',
        Result::BORKED => 'Borked'
    );

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

    /**
     * @param Phlack $phlack
     */
    public function __construct(Phlack $phlack)
    {
        $this->phlack = $phlack;
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildStarted(BuildRunEvent $event)
    {
        $build = $event->getBuild();

        $messageBuilder = $this->phlack->getMessageBuilder();

        $messageBuilder->setText(
            sprintf('Build [%s] (%s) started.', $build->getCn(), $build->getSuiteName())
        );

        $response = $this->phlack->send($messageBuilder->create());
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildFinished(BuildRunEvent $event)
    {
        $build  = $event->getBuild();
        $result = $event->getResult();

        $messageBuilder    = $this->phlack->getMessageBuilder();
        $attachmentBuilder = $messageBuilder->createAttachment();

        $messageBuilder->setText(
            sprintf('Build [%s] (%s) finished.', $build->getCn(), $build->getSuiteName())
        );

        $buildResult = $result->getBuildStatus();
        $color       = $this->resultColorMap[$buildResult];
        $text        = sprintf(
            '%s in %s.',
            $this->resultTextMap[$buildResult],
            $this->stringifyElapsedTime($result->getElapsedBuildTime())
        );

        $attachmentBuilder
            ->setFallback($text)
            ->setText($text)
            ->setColor($color);

//        foreach ($build->getTasks() as $task) {
//            $attachmentBuilder->addField(
//                $task->getName(),
//                $this->resultTextMap[$result->getTaskStatus($task)],
//                false
//            );
//        }

        $attachmentBuilder->end();

        $response = $this->phlack->send($messageBuilder->create());
    }

    /**
     * @param float $milliseconds
     * @return string
     */
    protected function stringifyElapsedTime($milliseconds)
    {
        $formatted = array();

        if ($milliseconds >= 86400000) {
            $days        = floor($milliseconds / 86400000);
            $formatted[] = $days . ' day' . ($days == 1 ? '' : 's');
            $milliseconds %= 86400000;
        }
        if ($milliseconds >= 3600000) {
            $hours       = floor($milliseconds / 3600000);
            $formatted[] = $hours . ' hour' . ($hours == 1 ? '' : 's');
            $milliseconds %= 3600000;
        }
        if ($milliseconds >= 60000) {
            $minutes     = floor($milliseconds / 60000);
            $formatted[] = $minutes . ' minute' . ($minutes == 1 ? '' : 's');
            $milliseconds %= 60000;
        }
        if ($milliseconds >= 1000) {
            $seconds     = floor($milliseconds / 1000);
            $formatted[] = $seconds . ' second' . ($seconds == 1 ? '' : 's');
            $milliseconds %= 1000;
        }
        if ($milliseconds) {
            $formatted[] = $milliseconds . ' millisecond' . ($milliseconds == 1 ? '' : 's');
        }

        return implode(', ', $formatted);
    }
}
