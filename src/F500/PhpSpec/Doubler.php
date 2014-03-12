<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\PhpSpec;

use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

/**
 * Class Stubber
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\PhpSpec
 */
class Doubler
{

    /**
     * @var Doubler
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $accumulatedCommandArgs = array();

    /**
     * @var array
     */
    private $accumulatedCommandEnvs = array();

    /**
     * @return Doubler
     */
    public static function get()
    {
        if (self::$instance === null) {
            self::$instance = new Doubler();
        }

        return self::$instance;
    }

    /**
     * @param Collaborator $command        F500\CI\Command\Command
     * @param Collaborator $commandFactory F500\CI\Command\CommandFactory
     */
    public function stubCommand(
        Collaborator $command,
        Collaborator $commandFactory = null
    ) {
        $command->getId()->willReturn('a1b2c3d4');
        $command->stringify(Argument::type('bool'))->willReturn('ls -l');
        $command->getResultCode()->willReturn(0);
        $command->getOutput()->willReturn('Some output...');
        $command->setResult(Argument::type('int'), Argument::type('string'))->willReturn('void');
        $command->clearResult()->willReturn('void');

        $hash = spl_object_hash($command);

        $this->accumulatedCommandArgs[$hash] = array();
        $accumulatedArgs = &$this->accumulatedCommandArgs[$hash];

        $this->accumulatedCommandEnvs[$hash] = array();
        $accumulatedEnvs = &$this->accumulatedCommandEnvs[$hash];

        $command->getArgs()->willReturn(array());
        $command->addArg(Argument::type('string'))->will(
            function ($args) use (&$accumulatedArgs) {
                $accumulatedArgs[] = $args[0];
                $this->getArgs()->willReturn($accumulatedArgs);
            }
        );

        $command->getCwd()->willReturn();
        $command->setCwd(Argument::type('string'))->will(
            function ($args) {
                $this->getCwd()->willReturn($args[0]);
            }
        );

        $accumulatedEnvs = array();
        $command->getEnv()->willReturn(array());
        $command->addEnv(Argument::type('string'), Argument::type('string'))->will(
            function ($args) use (&$accumulatedEnvs) {
                $accumulatedEnvs[$args[0]] = $args[1];
                $this->getEnv()->willReturn($accumulatedEnvs);
            }
        );

        if ($commandFactory) {
            $commandFactory->createCommand()->willReturn($command);
        }
    }

    /**
     * @param Collaborator $command        F500\CI\Command\StoreResultCommand
     * @param Collaborator $commandFactory F500\CI\Command\CommandFactory
     */
    public function stubStoreResultCommand(
        Collaborator $command,
        Collaborator $commandFactory = null
    ) {
        self::stubCommand($command);

        $command->setResultDirs(Argument::type('string'), Argument::type('string'))->willReturn('void');
        $command->setResultDirs(Argument::type('string'), Argument::type('string'), Argument::type('bool'))
            ->willReturn('void');

        $command->getSourceDir()->willReturn('/path/to/source');
        $command->getDestinationDir()->willReturn('/path/to/destination');

        if ($commandFactory) {
            $commandFactory->createStoreResultCommand()->willReturn($command);
        }
    }
}
