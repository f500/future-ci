<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Task\VagrantProvision;

use F500\CI\Build\Result;
use F500\CI\Task\Task;
use Prophecy\Argument;
use spec\F500\CI\Task\ResultParserSpec;

/**
 * Class VagrantProvisionResultParserSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Task\VagrantProvision
 */
class VagrantProvisionResultParserSpec extends ResultParserSpec
{

    protected $passedOutput = <<<'EOT'
==> testing: Running provisioner: ansible...

PLAY [testing] ****************************************************************

GATHERING FACTS ***************************************************************
ok: [testing.local]

PLAY RECAP ********************************************************************
testing.local              : ok=1    changed=0    unreachable=0    failed=0
EOT;

    protected $failedOutput = <<<'EOT'
==> testing: Running provisioner: ansible...

PLAY [testing] ****************************************************************

GATHERING FACTS ***************************************************************
ok: [testing.local]

PLAY RECAP ********************************************************************
testing.local              : ok=0    changed=0    unreachable=0    failed=1
EOT;

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Task\VagrantProvision\VagrantProvisionResultParser');
        $this->shouldImplement('F500\CI\Task\ResultParser');
    }

    function it_determines_if_the_task_has_passed(Task $task, Result $result)
    {
        $result->getTaskResults($task)->willReturn(
            array(
                'commands' => array(
                    'a1b2c3d4' => array(
                        'task'        => 'some_task',
                        'command_id'  => 'a1b2c3d4',
                        'command'     => '/usr/bin/env vagrant provision',
                        'result_code' => 0,
                        'output'      => $this->passedOutput
                    )
                )
            )
        );

        $result->markTaskAsPassed($task)
            ->willReturn()
            ->shouldBeCalled();

        $this->parse($task, $result);
    }

    function it_determines_if_the_task_has_failed(Task $task, Result $result)
    {
        $result->getTaskResults($task)->willReturn(
            array(
                'commands' => array(
                    'a1b2c3d4' => array(
                        'task'        => 'some_task',
                        'command_id'  => 'a1b2c3d4',
                        'command'     => '/usr/bin/env vagrant provision',
                        'result_code' => 0,
                        'output'      => $this->failedOutput
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
