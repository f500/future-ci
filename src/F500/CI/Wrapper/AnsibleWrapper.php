<?php

namespace F500\CI\Wrapper;

use F500\CI\Command\Command;

class AnsibleWrapper extends BaseWrapper
{

    /**
     * @param Command $command
     * @return Command
     */
    public function wrap(Command $command)
    {
        $options = $this->getOptions();

        $ansibleCommand = new Command();

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
            $ansibleCommand->addArg('--sudo');
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
            // $script[] = $name . '=' . escapeshellarg($value);
            $script[] = $name . '=' . escapeshellcmd($value);
        }

        foreach ($command->getArgs() as $arg) {
            // $script[] = escapeshellarg($arg);
            $script[] = escapeshellcmd($arg);
        }

        if ($command->getCwd()) {
            // $script[] = 'chdir=' . escapeshellarg($command->getCwd());
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
