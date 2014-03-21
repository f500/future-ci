<?php

/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$app['console']->add(new F500\CI\Console\Command\RunCommand());
$app['console']->add(new F500\CI\Console\Command\QueuePushCommand());
$app['console']->add(new F500\CI\Console\Command\QueueStatusCommand());
$app['console']->add(new F500\CI\Console\Command\QueueWorkerCommand());
