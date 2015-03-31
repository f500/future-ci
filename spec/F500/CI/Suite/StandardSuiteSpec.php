<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Suite;

use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Task\Task;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class StandardSuiteSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Suite
 */
class StandardSuiteSpec extends ObjectBehavior
{

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith(
            'some_suite',
            array(
                'suite' => array(
                    'name'  => 'Some Suite',
                    'cn'    => 'some_suite',
                    'class' => 'F500\CI\Suite\StandardSuite'
                ),
                'build' => array(
                    'class' => 'F500\CI\Build\StandardBuild'
                )
            )
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Suite\StandardSuite');
        $this->shouldImplement('F500\CI\Suite\Suite');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_suite');
    }

    function it_has_a_name_after_setting_it()
    {
        $this->setName('Some Suite');

        $this->getName()->shouldReturn('Some Suite');
    }

    function it_has_a_configuration()
    {
        $this->getConfig()->shouldReturn(
            array(
                'name'  => 'Some Suite',
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            )
        );
    }

    function it_can_add_a_task_to_itself(Task $task)
    {
        $this->addTask('some_task', $task);

        $this->getTasks()->shouldReturn(array('some_task' => $task));
    }

    function it_fails_adding_a_task_when_cn_already_exists(Task $task)
    {
        $this->addTask('some_task', $task);

        $this->shouldThrow('InvalidArgumentException')->during(
            'addTask',
            array('some_task', $task)
        );
    }

    function it_can_add_a_wrapper_to_itself(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);

        $this->getWrappers()->shouldReturn(array('some_wrapper' => $wrapper));
        $this->getWrapper('some_wrapper')->shouldReturn($wrapper);
    }

    function it_fails_adding_a_wrapper_when_cn_already_exists(Wrapper $wrapper)
    {
        $this->addWrapper('some_wrapper', $wrapper);

        $this->shouldThrow('InvalidArgumentException')->during(
            'addWrapper',
            array('some_wrapper', $wrapper)
        );
    }

    function it_does_not_have_a_wrapper_that_has_not_been_added()
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'getWrapper',
            array('some_wrapper')
        );
    }

    function it_turns_itself_into_json()
    {
        $json = <<< EOT
{
    "suite": {
        "name": "Some Suite",
        "cn": "some_suite",
        "class": "F500\\\\CI\\\\Suite\\\\StandardSuite"
    },
    "build": {
        "class": "F500\\\\CI\\\\Build\\\\StandardBuild"
    }
}
EOT;

        $this->toJson()->shouldReturn($json);
    }
}
