<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command;

use Prophecy\Argument;

/**
 * Class StoreResultCommandSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Command
 */
class StoreResultCommandSpec extends CommandSpec
{

    function it_does_not_have_a_source_dir_initially()
    {
        $this->shouldThrow('\RuntimeException')->during(
            'getSourceDir'
        );
    }

    function it_has_a_source_dir_with_trailing_slash_after_result_dirs_have_been_set()
    {
        $this->setResultDirs('/path/to/source', '/path/to/destination');

        $this->getSourceDir()->shouldReturn('/path/to/source/');
    }

    function it_does_not_have_a_destination_dir_initially()
    {
        $this->shouldThrow('\RuntimeException')->during(
            'getDestinationDir'
        );
    }

    function it_has_a_destination_dir_with_trailing_slash_after_result_dirs_have_been_set()
    {
        $this->setResultDirs('/path/to/source', '/path/to/destination');

        $this->getDestinationDir()->shouldReturn('/path/to/destination/');
    }

    function it_has_arguments_after_result_dirs_have_been_set()
    {
        $this->setResultDirs('/path/to/source', '/path/to/destination');

        $this->getArgs()->shouldReturn(array('cp', '-rp', '/path/to/source/', '/path/to/destination/'));
    }

    function it_does_not_have_arguments_after_result_dirs_have_been_set_with_add_args_turned_off()
    {
        $this->setResultDirs('/path/to/source', '/path/to/destination', false);

        $this->getArgs()->shouldReturn(array());
    }
}
