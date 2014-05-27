<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\AnsiblePlaybook;

use F500\CI\Build\Build;
use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\PhpSpec\Doubler;
use Prophecy\Argument;
use spec\F500\CI\Task\TaskSpec;

/**
 * Class AnsiblePlaybookTaskSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task\AnsiblePlaybook
 */
class AnsiblePlaybookTaskSpec extends TaskSpec
{

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\AnsiblePlaybook\AnsiblePlaybookTask');
        $this->shouldImplement('F500\CI\Task\Task');
    }

    function it_has_default_options()
    {
        $options = array(
            'cwd'        => '',
            'env'        => array(),
            'bin'        => '/usr/bin/env ansible-playbook',
            'playbook'   => '',
            'inventory'  => null,
            'limit'      => null,
            'extra_vars' => array()
        );

        $this->getOptions()->shouldReturn($options);
    }

    function it_has_other_options_after_setting_them()
    {
        $options = array(
            'cwd'        => '',
            'env'        => array(),
            'bin'        => '/usr/bin/env ansible-playbook',
            'playbook'   => 'provision.yml',
            'inventory'  => null,
            'limit'      => null,
            'extra_vars' => array(),
            'foo'        => 'bar'
        );

        $newoptions = array(
            'playbook' => 'provision.yml',
            'foo'      => 'bar'
        );

        $this->setOptions($newoptions);
        $this->getOptions()->shouldReturn($options);
    }

    function it_builds_commands(
        Build $build,
        CommandFactory $commandFactory,
        Command $command
    ) {
        Doubler::get()->stubCommand($command, $commandFactory);

        $this->setOptions(array('playbook' => 'provision.yml'));

        $commands = $this->buildCommands($build, $commandFactory);
        $commands->shouldBe(array($command));

        $commands[0]->getArgs()->shouldReturn(
            array(
                '/usr/bin/env ansible-playbook',
                "'provision.yml'"
            )
        );
    }
}
