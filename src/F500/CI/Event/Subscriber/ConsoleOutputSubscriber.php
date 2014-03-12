<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event\Subscriber;

use F500\CI\Build\Result;
use F500\CI\Event\BuildRunEvent;
use F500\CI\Event\Events;
use F500\CI\Event\TaskRunEvent;
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

    const UNICODE_BULLET = "\xE2\x88\x99";

    const UNICODE_CHECKMARK = "\xE2\x9C\x94";

    const UNICODE_BALLOT = "\xE2\x9C\x98";

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var array
     */
    protected $resultColorMap = array(
        Result::INCOMPLETE => 'magenta',
        Result::FAILED     => 'red',
        Result::SUCCESSFUL => 'green'
    );

    /**
     * @var array
     */
    protected $resultIconMap = array(
        Result::INCOMPLETE => self::UNICODE_BALLOT,
        Result::FAILED     => self::UNICODE_BALLOT,
        Result::SUCCESSFUL => self::UNICODE_CHECKMARK
    );

    /**
     * @var array
     */
    protected $resultTextMap = array(
        Result::INCOMPLETE => 'incomplete',
        Result::FAILED     => 'failed',
        Result::SUCCESSFUL => 'successful'
    );

    /**
     * @var int
     */
    protected $lineLength;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::BuildStarted  => array(
                array('onBuildStarted', 10)
            ),
            Events::TaskStarted   => array(
                array('onTaskStarted', 10)
            ),
            Events::TaskFinished  => array(
                array('onTaskFinished', 10)
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
     * @param BuildRunEvent $event
     */
    public function onBuildStarted(BuildRunEvent $event)
    {
        $build = $event->getBuild();

        $this->output->writeln(
            sprintf(
                'Running build <fg=yellow>%s</fg=yellow> (<fg=yellow>%s</fg=yellow>)',
                $build->getName(),
                $build->getDate()->format('Y-m-d H:i:s')
            )
        );
    }

    /**
     * @param TaskRunEvent $event
     */
    public function onTaskStarted(TaskRunEvent $event)
    {
        $task = $event->getTask();

        $line = sprintf(
            '<fg=yellow>%s</fg=yellow> Task <fg=yellow>%s</fg=yellow> ...',
            self::UNICODE_BULLET,
            $task->getName()
        );

        $this->lineLength = strlen(strip_tags($line));

        $this->output->write($line);
    }

    /**
     * @param TaskRunEvent $event
     */
    public function onTaskFinished(TaskRunEvent $event)
    {
        $task   = $event->getTask();
        $result = $event->getResult();

        $color  = $this->resultColorMap[$result->getOverallTaskResult($task)];
        $icon   = $this->resultIconMap[$result->getOverallTaskResult($task)];
        $text   = $this->resultTextMap[$result->getOverallTaskResult($task)];
        $repeat = max(78 - $this->lineLength - strlen($text), 0);

        $this->output->writeln(
            sprintf('%s <fg=%s>%s %s</fg=%s>', str_repeat('.', $repeat), $color, $icon, $text, $color)
        );
        $this->output->writeln(sprintf('  (took %s)', $this->stringifyElapsedTime($result->getElapsedTaskTime($task))));
    }

    /**
     * @param BuildRunEvent $event
     */
    public function onBuildFinished(BuildRunEvent $event)
    {
        $result = $event->getResult();

        $color = $this->resultColorMap[$result->getOverallBuildResult()];
        $icon  = $this->resultIconMap[$result->getOverallBuildResult()];
        $text  = $this->resultTextMap[$result->getOverallBuildResult()];

        $this->output->writeln(sprintf("\n<fg=%s>%s Build was %s</fg=%s>", $color, $icon, $text, $color));
        $this->output->writeln(sprintf('(took %s)', $this->stringifyElapsedTime($result->getElapsedBuildTime())));
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
