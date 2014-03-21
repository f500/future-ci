<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Codeception;

use F500\CI\Build\Result;
use F500\CI\Filesystem\Filesystem;
use F500\CI\Task\BaseResultParser;
use F500\CI\Task\Task;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Class CodeceptionResultParser
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\Result
 */
class CodeceptionResultParser extends BaseResultParser
{

    /**
     * @param Task   $task
     * @param Result $result
     */
    public function parse(Task $task, Result $result)
    {
        $report = $this->fixReport($result->getBuildDir($task), $result->getFilesystem());
        $report = json_decode($report, true);

        $passed = true;
        foreach ($report as $item) {
            if ($item['event'] == 'test' && $item['status'] !== 'pass') {
                $passed = false;
                break;
            }
        }

        if ($passed) {
            $result->markTaskAsPassed($task);
        } else {
            $result->markTaskAsFailed($task);
        }
    }

    /**
     * @param string     $buildDir
     * @param Filesystem $filesystem
     * @return false|string
     */
    protected function fixReport($buildDir, Filesystem $filesystem)
    {
        $filename = $buildDir . '/report.json';

        if (!$filesystem->exists($filename)) {
            return false;
        }

        try {
            $report = $filesystem->readFile($filename);

            $report = str_replace('}{', "},\n{", $report);
            $report = "[\n" . $report . "\n]";

            $filesystem->dumpFile($filename, $report);

            return $report;
        } catch (IOException $e) {
            return false;
        }
    }
}
