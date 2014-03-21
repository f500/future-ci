<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Build\Result;

/**
 * Class AlwaysPassResultParser
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class AlwaysPassResultParser extends BaseResultParser
{

    /**
     * @param Task   $task
     * @param Result $result
     */
    public function parse(Task $task, Result $result)
    {
        $result->markTaskAsPassed($task);
    }
}
