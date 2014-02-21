<?php

namespace spec\F500\CI\Wrapper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

abstract class WrapperSpec extends ObjectBehavior
{

    protected $defaultOptions = array();

    function let()
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith('some_wrapper');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Wrapper\AnsibleWrapper');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldReturn('some_wrapper');
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

}
