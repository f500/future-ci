<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\VagrantUp;

use F500\CI\Build\Result;
use F500\CI\Task\BaseResultParser;
use F500\CI\Task\Task;

/**
 * Class VagrantUpResultParser
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\VagrantUp
 */
class VagrantUpResultParser extends BaseResultParser
{

    /**
     * @param Task   $task
     * @param Result $result
     */
    public function parse(Task $task, Result $result)
    {
        $passed      = true;
        $taskResults = $result->getTaskResults($task);

        if (!empty($taskResults['commands'])) {
            foreach ($taskResults['commands'] as $commandResults) {
                if (!empty($commandResults['output'])) {
                    if (preg_match('/Unclean result code: \d+/', $commandResults['output'])) {
                        $passed = false;
                    }
                }
            }
        }

        if ($passed) {
            $result->markTaskAsPassed($task);
        } else {
            $result->markTaskAsFailed($task);
        }
    }
}
