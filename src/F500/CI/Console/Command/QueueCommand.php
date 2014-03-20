<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueueCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class QueueCommand extends Command
{

    /**
     * @var \Pheanstalk_Pheanstalk
     */
    protected $pheanstalk;

    protected function configure()
    {
        $this
            ->addOption(
                'server',
                's',
                InputOption::VALUE_OPTIONAL,
                'The beanstalkd server to connect to.',
                '127.0.0.1'
            )
            ->addOption(
                'port',
                'p',
                InputOption::VALUE_OPTIONAL,
                'The port to connect to.',
                11300
            )
            ->addOption(
                'connect-timeout',
                't',
                InputOption::VALUE_OPTIONAL,
                'The connection timeout to use (in seconds).',
                \Pheanstalk_Connection::DEFAULT_CONNECT_TIMEOUT
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getContainer();

        if ($server = $input->getOption('server')) {
            $app['pheanstalk.server'] = $server;
        }
        if ($port = $input->getOption('port')) {
            $app['pheanstalk.port'] = (int)$port;
        }
        if ($timeout = $input->getOption('connect-timeout')) {
            $app['pheanstalk.connect_timeout'] = (int)$timeout;
        }

        $this->pheanstalk = $app['pheanstalk'];
    }
}
