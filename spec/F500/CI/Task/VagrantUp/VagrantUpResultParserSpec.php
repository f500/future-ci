<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\VagrantUp;

use F500\CI\Build\Result;
use F500\CI\Task\Task;
use Prophecy\Argument;
use spec\F500\CI\Task\ResultParserSpec;

/**
 * Class VagrantUpResultParserSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task\VagrantUp
 */
class VagrantUpResultParserSpec extends ResultParserSpec
{

    protected $successfulOutput = <<<'EOT'
Bringing machine 'testing' up with 'virtualbox' provider...
[testing] Clearing any previously set forwarded ports...
[testing] Creating shared folders metadata...
[testing] Clearing any previously set network interfaces...
[testing] Preparing network interfaces based on configuration...
[testing] Forwarding ports...
[testing] -- 22 => 2222 (adapter 1)
[testing] Running 'pre-boot' VM customizations...
[testing] Booting VM...
[testing] Waiting for machine to boot. This may take a few minutes...
[testing] Machine booted and ready!
[testing] Setting hostname...
[testing] Configuring and enabling network interfaces...
[testing] Mounting shared folders...
EOT;

    protected $unsuccessfulOutput = <<<'EOT'
/path/to/project/Vagrantfile:82:in `block (2 levels) in <top (required)>': undefined local variable or method `onfig' for main:Object (NameError)
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/v2/loader.rb:37:in `call'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/v2/loader.rb:37:in `load'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/loader.rb:104:in `block (2 levels) in load'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/loader.rb:98:in `each'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/loader.rb:98:in `block in load'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/loader.rb:95:in `each'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/config/loader.rb:95:in `load'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:329:in `machine'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/plugin/v2/command.rb:134:in `block in with_target_vms'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/plugin/v2/command.rb:158:in `call'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/plugin/v2/command.rb:158:in `block in with_target_vms'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/plugin/v2/command.rb:140:in `each'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/plugin/v2/command.rb:140:in `with_target_vms'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/plugins/commands/up/command.rb:52:in `block in execute'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:206:in `block (2 levels) in batch'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:204:in `tap'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:204:in `block in batch'
	from <internal:prelude>:10:in `synchronize'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:203:in `batch'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/plugins/commands/up/command.rb:51:in `execute'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/cli.rb:38:in `execute'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/lib/vagrant/environment.rb:478:in `cli'
	from /Applications/Vagrant/embedded/gems/gems/vagrant-1.3.5/bin/vagrant:96:in `<top (required)>'
	from /Applications/Vagrant/bin/../embedded/gems/bin/vagrant:23:in `load'
	from /Applications/Vagrant/bin/../embedded/gems/bin/vagrant:23:in `<main>'
Unclean result code: 1
EOT;

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\VagrantUp\VagrantUpResultParser');
        $this->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_determines_if_a_result_is_successful(Task $task, Result $result)
    {
        $result->getTaskResults($task)->willReturn(
            array(
                'commands' => array(
                    'a1b2c3d4' => array(
                        'task'        => 'some_task',
                        'command_id'  => 'a1b2c3d4',
                        'command'     => '/usr/bin/env vagrant up --no-provision',
                        'result_code' => 0,
                        'output'      => $this->successfulOutput
                    )
                )
            )
        );

        $result->markTaskAsSuccessful($task)
            ->willReturn()
            ->shouldBeCalled();

        $this->parse($task, $result);
    }

    function it_determines_if_a_result_is_unsuccessful(Task $task, Result $result)
    {
        $result->getTaskResults($task)->willReturn(
            array(
                'commands' => array(
                    'a1b2c3d4' => array(
                        'task'        => 'some_task',
                        'command_id'  => 'a1b2c3d4',
                        'command'     => '/usr/bin/env vagrant up --no-provision',
                        'result_code' => 0,
                        'output'      => $this->unsuccessfulOutput
                    )
                )
            )
        );

        $result->markTaskAsFailed($task)
            ->willReturn()
            ->shouldBeCalled();

        $this->parse($task, $result);
    }
}
