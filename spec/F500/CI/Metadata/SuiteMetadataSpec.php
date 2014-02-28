<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Metadata;

use F500\CI\Suite\Suite;
use Prophecy\Argument;

/**
 * Class SuiteMetadataSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Metadata
 */
class SuiteMetadataSpec extends MetadataSpec
{

    function let(Suite $suite)
    {
        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($suite);

        $suite->setMetadata($this->getWrappedObject())->shouldBeCalled();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Metadata\SuiteMetadata');
        $this->shouldImplement('F500\CI\Metadata\Metadata');
    }
}
