<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;

/**
 * Class CleanResultCodeWrapper
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command\Wrapper
 */
class CleanResultCodeWrapper extends BaseWrapper
{
    /**
     * @param Command        $command
     * @param CommandFactory $commandFactory
     * @return Command
     * @throws \RuntimeException
     */
    public function wrap(Command $command, CommandFactory $commandFactory)
    {
        $wrappedCommand = $commandFactory->createCommand();

        foreach ($command->getArgs() as $arg) {
            $wrappedCommand->addArg($arg);
        }

        $wrappedCommand->addArg('||');
        $wrappedCommand->addArg('echo');
        $wrappedCommand->addArg('"Unclean result code: $?"');

        $wrappedCommand->setCwd($command->getCwd());

        foreach ($command->getEnv() as $env => $value) {
            $wrappedCommand->addEnv($env, $value);
        }

        return $wrappedCommand;
    }
}
