<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Metadata;

use F500\CI\Build\Build;
use Prophecy\Argument;

/**
 * Class BuildMetadataSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Metadata
 */
class BuildMetadataSpec extends MetadataSpec
{

    function let(Build $build)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($build);

        $build->setMetadata($this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Metadata\BuildMetadata');
        $this->shouldImplement('F500\CI\Metadata\Metadata');
    }
}
