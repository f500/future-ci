<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

/**
 * Class Events
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
class Events
{

    /**
     * Dispatched after a build has been initialized.
     */
    const BuildInitialized = 'BuildInitialized';

    /**
     * Dispatched before a build starts running.
     */
    const BuildStarted = 'BuildStarted';

    /**
     * Dispatched before a suite starts running.
     */
    const SuiteStarted = 'SuiteStarted';

    /**
     * Dispatched before a task starts running.
     */
    const TaskStarted = 'TaskStarted';

    /**
     * Dispatched before a command starts execution.
     */
    const CommandStarted = 'CommandStarted';

    /**
     * Dispatched after a command has finished execution.
     */
    const CommandFinished = 'CommandFinished';

    /**
     * Dispatched after a task has finished running.
     */
    const TaskFinished = 'TaskFinished';

    /**
     * Dispatched after a suite has finished running.
     */
    const SuiteFinished = 'SuiteFinished';

    /**
     * Dispatched after a build has finished running.
     */
    const BuildFinished = 'BuildFinished';

    /**
     * Dispatched after a buils has been cleaned up.
     */
    const BuildCleanedUp = 'BuildCleanedUp';
}
