<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class FormatterFactorySpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class FormatterFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\FormatterFactory');
    }

    function it_creates_a_formatter()
    {
        $formatter = $this->createFormatter('F500\CI\Task\Dummy\DummyFormatter', 'some_formatter');

        $formatter->shouldHaveType('F500\CI\Task\Dummy\DummyFormatter');
        $formatter->shouldImplement('F500\CI\Task\Formatter');
    }

    function it_fails_to_create_a_formatter_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createFormatter',
            array('NonExistent\Formatter', 'some_formatter')
        );
    }

    function it_fails_to_create_a_formatter_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createFormatter',
            array('StdClass', 'some_parser')
        );
    }
}
