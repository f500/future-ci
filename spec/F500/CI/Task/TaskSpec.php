<?php

namespace spec\F500\CI\Task;

use F500\CI\Suite\Suite;
use F500\CI\Wrapper\Wrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

abstract class TaskSpec extends ObjectBehavior
{

    protected $defaultOptions = array();

    function let(Suite $suite)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_task', $suite);

        $suite->addTask('some_task', $this->getWrappedObject())->shouldBeCalled();
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_task');
    }

    function it_has_a_suite(Suite $suite)
    {
        $this->getSuite()->shouldReturn($suite);
    }

    function it_has_a_name_after_setting_it()
    {
        $name = 'Some Task';

        $this->setName($name);
        $this->getName()->shouldReturn($name);
    }

    function it_has_default_options()
    {
        $this->getOptions()->shouldReturn($this->defaultOptions);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array_replace_recursive(
            $this->defaultOptions,
            array(
                'some_option'  => 'foo',
                'other_option' => 'bar'
            )
        );

        $this->setOptions($options);
        $this->getOptions()->shouldReturn($options);
    }

    function it_has_wrappers_after_setting_them()
    {
        $this->setWrappers(array('some_wrapper'));
        $this->getWrappers()->shouldReturn(array('some_wrapper'));
    }

    function it_fails_setting_wrappers_if_duplicates_exist(Wrapper $wrapper)
    {
        $this->shouldThrow('InvalidArgumentException')->during(
            'setWrappers',
            array(array('some_wrapper', 'some_wrapper'))
        );
    }
}
