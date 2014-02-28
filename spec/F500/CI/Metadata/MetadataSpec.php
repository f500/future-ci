<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Metadata;

use PhpSpec\ObjectBehavior;

/**
 * Class MetadataSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Metadata
 */
abstract class MetadataSpec extends ObjectBehavior
{

    function it_has_no_elapsed_time_initially()
    {
        $this->getElapsedTime()->shouldReturn(null);
    }

    function it_has_elapsed_time_after_setting_it()
    {
        $this->setElapsedTime(123456789);

        $this->getElapsedTime()->shouldReturn((float)123456789);
    }

    function it_can_stringify_elapsed_time()
    {
        $this->setElapsedTime(123456789);

        $this->stringifyElapsedTime()->shouldReturn('1 day, 10 hours, 17 minutes, 36 seconds, 789 milliseconds');
    }
}
