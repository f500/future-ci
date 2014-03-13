<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\StoreResultCommand;

/**
 * Class AnsibleWrapper
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Wrapper
 */
class AnsibleWrapper extends BaseWrapper
{

    /**
     * @param Command        $command
     * @param CommandFactory $commandFactory
     * @return Command
     * @throws \RuntimeException
     */
    public function wrap(Command $command, CommandFactory $commandFactory)
    {
        $options = $this->getOptions();

        if (empty($options['bin'])) {
            throw new \RuntimeException(
                sprintf(
                    'Wrapper "%s" in suite "%s" has no "bin" configured.',
                    $this->getCn(),
                    $this->getSuite()->getCn()
                )
            );
        }
        if (empty($options['host'])) {
            throw new \RuntimeException(
                sprintf(
                    'Wrapper "%s" in suite "%s" has no "host" configured.',
                    $this->getCn(),
                    $this->getSuite()->getCn()
                )
            );
        }
        if (empty($options['inventory'])) {
            throw new \RuntimeException(
                sprintf(
                    'Wrapper "%s" in suite "%s" has no "inventory" configured.',
                    $this->getCn(),
                    $this->getSuite()->getCn()
                )
            );
        }

        if ($command instanceof StoreResultCommand) {
            $wrappedCommand = $commandFactory->createStoreResultCommand();
            $wrappedCommand->setResultDirs($command->getSourceDir(), $command->getDestinationDir(), false);
        } else {
            $wrappedCommand = $commandFactory->createCommand();
        }

        $wrappedCommand->addArg($options['bin']);
        $wrappedCommand->addArg($options['host']);
        $wrappedCommand->addArg('--inventory-file=' . $options['inventory']);

        if (!empty($options['limit'])) {
            $wrappedCommand->addArg('--limit=' . $options['limit']);
        }
        if (!empty($options['user'])) {
            $wrappedCommand->addArg('--user=' . $options['user']);
        }
        if (!empty($options['private_key'])) {
            $wrappedCommand->addArg('--private-key=' . $options['private_key']);
        }
        if (!empty($options['sudo'])) {
            if ($options['sudo'] === true || $options['sudo'] === 'true') {
                $wrappedCommand->addArg('--sudo');
            } else {
                $wrappedCommand->addArg('--sudo-user=' . $options['sudo']);
            }
        }
        if (!empty($options['module_path'])) {
            $wrappedCommand->addArg('--module-path=' . $options['module_path']);
        }
        if (!empty($options['timeout'])) {
            $wrappedCommand->addArg('--timeout=' . $options['timeout']);
        }
        if (!empty($options['verbose'])) {
            $wrappedCommand->addArg('-' . str_repeat('v', $options['verbose']));
        }

        if ($command instanceof StoreResultCommand) {
            $wrappedCommand->addArg('-m');
            $wrappedCommand->addArg('synchronize');
            $wrappedCommand->addArg('-a');
            $wrappedCommand->addArg($this->buildStoreResultScript($command));
        } else {
            $wrappedCommand->addArg('-m');
            $wrappedCommand->addArg('shell');
            $wrappedCommand->addArg('-a');
            $wrappedCommand->addArg($this->buildScript($command));
        }

        if (!empty($options['env'])) {
            foreach ($options['env'] as $name => $value) {
                $wrappedCommand->addEnv($name, $value);
            }
        }

        return $wrappedCommand;
    }

    /**
     * @param Command $command
     * @return array
     */
    protected function buildScript(Command $command)
    {
        $script = array();

        foreach ($command->getEnv() as $name => $value) {
            $script[] = $name . '=' . escapeshellcmd($value);
        }

        foreach ($command->getArgs() as $arg) {
            $script[] = escapeshellcmd($arg);
        }

        if ($command->getCwd()) {
            $script[] = 'chdir=' . escapeshellcmd($command->getCwd());
        }

        return "'" . implode(' ', $script) . "'";
    }

    /**
     * @param StoreResultCommand $command
     * @return array
     */
    protected function buildStoreResultScript(StoreResultCommand $command)
    {
        $options = $this->getOptions();

        $script = array(
            'archive=yes',
            'delete=no',
            'dest=' . $command->getDestinationDir(),
            'mode=pull',
            'rsync_path=' . $options['rsync_bin'],
            'src=' . $command->getSourceDir()
        );

        return "'" . implode(' ', $script) . "'";
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            'env'         => array(),
            'bin'         => '/usr/bin/env ansible',
            'rsync_bin'   => '/usr/bin/env rsync',
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
    }
}
