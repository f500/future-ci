<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueueStatusCommand
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class QueueStatusCommand extends QueueCommand
{

    protected function configure()
    {
        $this
            ->setName('queue:status')
            ->setDescription('Checks the status of the job-queue.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws \RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->pheanstalk->getConnection();

        $listening = $connection->isServiceListening();

        $output->writeln("\n                 Server status");
        $output->writeln('                 -------------');

        $output->writeln(sprintf('                       Server: <fg=yellow>%s</fg=yellow>', $connection->getHost()));
        $output->writeln(sprintf('                         Post: <fg=yellow>%s</fg=yellow>', $connection->getPort()));
        $output->writeln(
            sprintf('                      Timeout: <fg=yellow>%s</fg=yellow>', $connection->getConnectTimeout())
        );
        $output->writeln(
            sprintf(
                '                    Listening: %s',
                $listening ? '<fg=green>yes</fg=green>' : '<fg=red>no</fg=red>'
            )
        );

        if ($listening) {
            foreach ($this->pheanstalk->listTubes() as $tube) {
                $stats = $this->pheanstalk->statsTube($tube);

                $output->writeln(
                    sprintf(
                        "\n%sTube <fg=yellow>%s</fg=yellow> status",
                        str_repeat(' ', max(18 - strlen($tube), 0)),
                        $tube
                    )
                );
                $output->writeln(
                    sprintf('%s%s', str_repeat(' ', max(18 - strlen($tube), 0)), str_repeat('-', strlen($tube) + 12))
                );

                $output->writeln(
                    sprintf('          Current urgent jobs: <fg=yellow>%d</fg=yellow>', $stats['current-jobs-urgent'])
                );
                $output->writeln(
                    sprintf('           Current ready jobs: <fg=yellow>%d</fg=yellow>', $stats['current-jobs-ready'])
                );
                $output->writeln(
                    sprintf('        Current reserved jobs: <fg=yellow>%d</fg=yellow>', $stats['current-jobs-reserved'])
                );
                $output->writeln(
                    sprintf('         Current delayed jobs: <fg=yellow>%d</fg=yellow>', $stats['current-jobs-delayed'])
                );
                $output->writeln(
                    sprintf('          Current buried jobs: <fg=yellow>%d</fg=yellow>', $stats['current-jobs-buried'])
                );
                $output->writeln(
                    sprintf('                   Total jobs: <fg=yellow>%d</fg=yellow>', $stats['total-jobs'])
                );
                $output->writeln(
                    sprintf('     Current open connections: <fg=yellow>%d</fg=yellow>', $stats['current-using'])
                );
                $output->writeln(
                    sprintf(' Current watching connections: <fg=yellow>%d</fg=yellow>', $stats['current-watching'])
                );
                $output->writeln(
                    sprintf('  Current waiting connections: <fg=yellow>%d</fg=yellow>', $stats['current-waiting'])
                );
                $output->writeln(
                    sprintf('              Delete commands: <fg=yellow>%d</fg=yellow>', $stats['cmd-delete'])
                );
                $output->writeln(
                    sprintf('          Pause tube commands: <fg=yellow>%d</fg=yellow>', $stats['cmd-pause-tube'])
                );
                $output->writeln(sprintf('                       Paused: <fg=yellow>%d</fg=yellow>', $stats['pause']));
                $output->writeln(
                    sprintf('              Pause time left: <fg=yellow>%d</fg=yellow>', $stats['pause-time-left'])
                );
            }
        }
    }
}
