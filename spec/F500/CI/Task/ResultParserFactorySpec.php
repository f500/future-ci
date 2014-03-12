<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ResultParserFactorySpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task
 */
class ResultParserFactorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\ResultParserFactory');
    }

    function it_creates_a_result_parser()
    {
        $resultParser = $this->createResultParser('F500\CI\Task\Dummy\DummyResultParser', 'some_parser');

        $resultParser->shouldHaveType('F500\CI\Task\Dummy\DummyResultParser');
        $resultParser->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_fails_to_create_a_result_parser_when_class_does_not_exist()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createResultParser',
            array('NonExistent\ResultParser', 'some_parser')
        );
    }

    function it_fails_to_create_a_result_parser_when_interface_not_implemented()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'createResultParser',
            array('StdClass', 'some_parser')
        );
    }
}
