<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Dummy;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Task\BaseTask;

/**
 * Class DummyTask
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
class DummyTask extends BaseTask
{

    /**
     * @param Build          $build
     * @param CommandFactory $commandFactory
     * @return Command[]
     */
    public function buildCommands(Build $build, CommandFactory $commandFactory)
    {
        return array();
    }
}
