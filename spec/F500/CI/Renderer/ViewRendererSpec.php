<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Renderer;

use F500\CI\Renderer\Types\RenderType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ViewRendererSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Renderer
 */
class ViewRendererSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Renderer\ViewRenderer');
    }

    function it_renders_a_view_using_a_rendertype(RenderType $type)
    {
        $this->registerType('text', $type);
        $this->render('text', 'some_template');

        $type->render(Argument::type('string'), Argument::type('array'))->shouldHaveBeenCalled();
    }
}
