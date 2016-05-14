<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class BuildInfoSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class BuildInfoSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('F500\CI\Build\BuildInfo');
    }

    function it_contains_valid_data()
    {
        $rawInfo = [
            'author' => 'Ramon',
            'branch' => 'feature/some-shiny-feature',
            'comment' => 'Comment about feature',
            'compare' => 'https://github.com/Future500bv',
            'repo' => 'repo-name',
        ];
        $this->beConstructedWith($rawInfo);

        $this->getAuthor()->shouldReturn($rawInfo['author']);
        $this->getBranch()->shouldReturn($rawInfo['branch']);
        $this->getComment()->shouldReturn($rawInfo['comment']);
        $this->getCompare()->shouldReturn($rawInfo['compare']);
        $this->getRepo()->shouldReturn($rawInfo['repo']);
    }

    function it_always_returns_a_string()
    {
        $this->beConstructedWith([]);

        $this->getAuthor()->shouldBeString();
        $this->getBranch()->shouldBeString();
        $this->getComment()->shouldBeString();
        $this->getCompare()->shouldBeString();
        $this->getRepo()->shouldBeString();
    }

}
