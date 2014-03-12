<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Filesystem;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class FilesystemSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Filesystem
 */
class FilesystemSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Filesystem\Filesystem');
        $this->shouldBeAnInstanceOf('Symfony\Component\Filesystem\Filesystem');
    }

    function it_reads_a_file()
    {
        $file    = realpath(__DIR__ . '/../../../data/suites/blank_suite.yml');
        $content = "name: Blank Suite\n";

        $this->readFile($file)->shouldReturn($content);
    }
}
