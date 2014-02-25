<?php

namespace F500\CI\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;

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
            throw new \RuntimeException(sprintf(
                'Wrapper "%s" in suite "%s" has no "bin" configured.',
                $this->getCn(),
                $this->getSuite()->getCn()
            ));
        }
        if (empty($options['host'])) {
            throw new \RuntimeException(sprintf(
                'Wrapper "%s" in suite "%s" has no "host" configured.',
                $this->getCn(),
                $this->getSuite()->getCn()
            ));
        }
        if (empty($options['inventory'])) {
            throw new \RuntimeException(sprintf(
                'Wrapper "%s" in suite "%s" has no "inventory" configured.',
                $this->getCn(),
                $this->getSuite()->getCn()
            ));
        }

        $ansibleCommand = $commandFactory->create();

        $ansibleCommand->addArg($options['bin']);
        $ansibleCommand->addArg($options['host']);
        $ansibleCommand->addArg('--inventory-file=' . $options['inventory']);

        if (!empty($options['limit'])) {
            $ansibleCommand->addArg('--limit=' . $options['limit']);
        }
        if (!empty($options['user'])) {
            $ansibleCommand->addArg('--user=' . $options['user']);
        }
        if (!empty($options['private_key'])) {
            $ansibleCommand->addArg('--private-key=' . $options['private_key']);
        }
        if (!empty($options['sudo'])) {
            if ($options['sudo'] === true || $options['sudo'] === 'true') {
                $ansibleCommand->addArg('--sudo');
            } else {
                $ansibleCommand->addArg('--sudo-user=' . $options['sudo']);
            }
        }
        if (!empty($options['module_path'])) {
            $ansibleCommand->addArg('--module-path=' . $options['module_path']);
        }
        if (!empty($options['timeout'])) {
            $ansibleCommand->addArg('--timeout=' . $options['timeout']);
        }
        if (!empty($options['verbose'])) {
            $ansibleCommand->addArg('-' . str_repeat('v', $options['verbose']));
        }

        $ansibleCommand->addArg('-m');
        $ansibleCommand->addArg('shell');
        $ansibleCommand->addArg('-a');
        $ansibleCommand->addArg($this->buildScript($command));

        return $ansibleCommand;
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

        return implode(' ', $script);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            'bin'         => '/usr/bin/ansible',
            'host'        => null,
            'inventory'   => null,
            'limit'       => null,
            'user'        => null,
            'private_key' => null,
            'sudo'        => false,
            'module_path' => null,
            'timeout'     => 10,
            'verbose'     => 0
        );
    }
}