<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace %namespace%;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class %name%
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   %namespace%
 */
class %name% extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('%subject%');
    }
}
