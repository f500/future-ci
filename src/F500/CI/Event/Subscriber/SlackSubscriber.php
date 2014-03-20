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

        $messageBuilder    = $this->phlack->getMessageBuilder();
        $attachmentBuilder = $messageBuilder->createAttachment();

        $attachmentBuilder
            ->setText('Build started: ' . $build->getCn())
            ->end();

        $response = $this->phlack->send($messageBuilder->create());
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildFinished(BuildRunEvent $event)
    {
        $build = $event->getBuild();
        $result = $event->getResult();

        $messageBuilder = $this->phlack->getMessageBuilder();

        $attachmentBuilder = $messageBuilder->createAttachment();
        $attachmentBuilder->setText('Build finished: ' . $build->getCn());

        switch ($result->getOverallBuildResult()) {
            case Result::SUCCESSFUL:
                $attachmentBuilder->setPreText('success');
                $attachmentBuilder->setColor('good');
                break;
            case Result::FAILED:
                $attachmentBuilder->setPreText('failure');
                $attachmentBuilder->setColor('warning');
                break;
            case Result::INCOMPLETE:
                $attachmentBuilder->setPreText('incomplete');
                $attachmentBuilder->setColor('danger');
                break;
        }

        $attachmentBuilder->end();

        $response = $this->phlack->send($messageBuilder->create());
    }
}
