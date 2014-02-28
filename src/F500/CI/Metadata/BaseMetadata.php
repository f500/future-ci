<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Metadata;

/**
 * Class BaseMetadata
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Metadata
 */
class BaseMetadata implements Metadata
{

    /**
     * @var float
     */
    protected $elapsedTime;

    /**
     * @param float $elapsedTime
     */
    public function setElapsedTime($elapsedTime)
    {
        $this->elapsedTime = (float)$elapsedTime;
    }

    /**
     * @return float
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }

    /**
     * @return string
     */
    public function stringifyElapsedTime()
    {
        $milliseconds = $this->elapsedTime;
        $formatted    = array();

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
