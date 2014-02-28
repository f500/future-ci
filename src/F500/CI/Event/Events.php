<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Event;

/**
 * Class Events
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Event
 */
class Events
{
    const BuildStarted = 'BuildStarted';

    const BuildFinished = 'BuildFinished';

    const SuiteStarted = 'SuiteStarted';

    const SuiteFinished = 'SuiteFinished';

    const TaskStarted = 'TaskStarted';

    const TaskFinished = 'TaskFinished';
}
