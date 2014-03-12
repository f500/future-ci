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
class Stubber
{

    /**
     * @param Collaborator $command        F500\CI\Command\Command
     * @param Collaborator $commandFactory F500\CI\Command\CommandFactory
     */
    public static function stubCommand(
        Collaborator $command,
        Collaborator $commandFactory = null
    ) {
        $command->getId()->willReturn('a1b2c3d4');
        $command->getArgs()->willReturn(array('ls', '-l'));
        $command->addArg(Argument::type('string'))->willReturn('void');
        $command->getCwd()->willReturn(null);
        $command->setCwd(Argument::type('string'))->willReturn('void');
        $command->getEnv()->willReturn(array());
        $command->addEnv(Argument::type('string'), Argument::type('string'))->willReturn('void');
        $command->stringify(Argument::type('bool'))->willReturn('ls -l');
        $command->getResultCode()->willReturn(0);
        $command->getOutput()->willReturn('Some output...');
        $command->setResult(Argument::type('int'), Argument::type('string'))->willReturn('void');
        $command->clearResult()->willReturn('void');

        if ($commandFactory) {
            $commandFactory->createCommand()->willReturn($command);
        }
    }

    /**
     * @param Collaborator $command        F500\CI\Command\StoreResultCommand
     * @param Collaborator $commandFactory F500\CI\Command\CommandFactory
     */
    public static function stubStoreResultCommand(
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
