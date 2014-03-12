<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;
use F500\PhpSpec\Doubler;
use Prophecy\Argument;

/**
 * Class AnsibleWrapperSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Wrapper
 */
class AnsibleWrapperSpec extends WrapperSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Command\Wrapper\AnsibleWrapper');
        $this->shouldImplement('F500\CI\Command\Wrapper\Wrapper');
    }

    function it_has_default_options()
    {
        $options = array(
            'env'         => array(),
            'bin'         => '/usr/bin/ansible',
            'rsync_bin'   => '/usr/bin/rsync',
            'host'        => '',
            'inventory'   => '',
            'limit'       => '',
            'user'        => '',
            'private_key' => '',
            'sudo'        => false,
            'module_path' => '',
            'timeout'     => 10,
            'verbose'     => 0
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array(
            'env'         => array(),
            'bin'         => '/usr/bin/ansible',
            'rsync_bin'   => '/usr/bin/rsync',
            'host'        => 'testing',
            'inventory'   => '',
            'limit'       => '',
            'user'        => '',
            'private_key' => '',
            'sudo'        => true,
            'module_path' => '',
            'timeout'     => 10,
            'verbose'     => 0,
            'foo'         => 'bar'
        );

        $this->setOptions(
            array(
                'host' => 'testing',
                'sudo' => true,
                'foo'  => 'bar'
            )
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_can_wrap_a_command_in_a_new_command(
        CommandFactory $commandFactory,
        Command $oldCommand,
        Command $newCommand
    ) {
        $oldCommand->getArgs()->willReturn(array('ls', '-l'));
        $oldCommand->getCwd()->willReturn('/tmp');
        $oldCommand->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));

        Doubler::get()->stubCommand($newCommand, $commandFactory);

        $this->setOptions(array('host' => 'localhost', 'inventory' => '/etc/ansible/hosts'));

        $wrappedCommand = $this->wrap($oldCommand, $commandFactory);
        $wrappedCommand->shouldImplement('F500\CI\Command\Command');
        $wrappedCommand->getArgs()->shouldReturn(
            array(
                '/usr/bin/ansible',
                'localhost',
                '--inventory-file=/etc/ansible/hosts',
                '--timeout=10',
                '-m',
                'shell',
                '-a',
                "'PATH=/usr/local/bin:/usr/bin:/bin ls -l chdir=/tmp'"
            )
        );
        $wrappedCommand->getCwd()->shouldReturn(null);
        $wrappedCommand->getEnv()->shouldReturn(array());
    }

    function it_can_wrap_a_store_result_command_in_a_new_command(
        CommandFactory $commandFactory,
        StoreResultCommand $oldCommand,
        StoreResultCommand $newCommand
    ) {
        $oldCommand->getEnv()->willReturn(array('PATH' => '/usr/local/bin:/usr/bin:/bin'));
        $oldCommand->getSourceDir()->willReturn('/path/to/source/');
        $oldCommand->getDestinationDir()->willReturn('/path/to/destination/');

        Doubler::get()->stubStoreResultCommand($newCommand, $commandFactory);

        $this->setOptions(array('host' => 'localhost', 'inventory' => '/etc/ansible/hosts'));

        $wrappedCommand = $this->wrap($oldCommand, $commandFactory);
        $wrappedCommand->shouldImplement('F500\CI\Command\StoreResultCommand');
        $wrappedCommand->getArgs()->shouldReturn(
            array(
                '/usr/bin/ansible',
                'localhost',
                '--inventory-file=/etc/ansible/hosts',
                '--timeout=10',
                '-m',
                'synchronize',
                '-a',
                "'archive=yes delete=no dest=/path/to/destination/ mode=pull rsync_path=/usr/bin/rsync src=/path/to/source/'"
            )
        );
        $wrappedCommand->getCwd()->shouldReturn(null);
        $wrappedCommand->getEnv()->shouldReturn(array());
    }
}
