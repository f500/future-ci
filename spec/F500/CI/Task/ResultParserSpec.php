<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ResultParserSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
abstract class ResultParserSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_parser');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_parser');
    }

    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn(array());
    }

    function it_has_other_options_after_setting_them()
    {
        $this->setOptions(array('some_option' => 'foo', 'other_option' => 'bar'));

        $this->getOptions()->shouldReturn(array('some_option' => 'foo', 'other_option' => 'bar'));
    }
}
