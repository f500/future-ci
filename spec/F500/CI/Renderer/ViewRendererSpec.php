<?php

namespace spec\F500\CI\Renderer;

use F500\CI\Renderer\Types\RenderType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ViewRendererSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Renderer\ViewRenderer');
    }

    function it_renders_a_view_using_a_rendertype(RenderType $type)
    {
        $type->render(Argument::type('string'), Argument::type('array'))->shouldBeCalled();

        $this->registerType('text', $type);
        $this->render('text', 'some_template');
    }
}
