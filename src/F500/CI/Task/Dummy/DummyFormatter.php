<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Dummy;

use F500\CI\Build\Result;
use F500\CI\Task\BaseFormatter;
use F500\CI\Task\Task;

/**
 * Class DummyFormatter
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\Result
 */
class DummyFormatter extends BaseFormatter
{

    /**
     * @param Task   $task
     * @param Result $result
     */
    public function format(Task $task, Result $result)
    {
        // Does nothing
    }
}
