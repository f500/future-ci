<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Renderer\Types;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class PhpRenderTypeSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Renderer\Types
 */
class PhpRenderTypeSpec extends ObjectBehavior
{

    protected $text = <<<'EOT'
<!DOCTYPE html>
<html>
    <body>
        <h1>Hello World!</h1>
    </body>
</html>

EOT;

    function let()
    {
        $viewDir = __DIR__ . '/../../../../data/views';

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($viewDir);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Renderer\Types\PhpRenderType');
    }

    function it_renders_php()
    {
        $this->render('php_render_type', array('header' => 'Hello World!'))->shouldReturn($this->text);
    }
}
