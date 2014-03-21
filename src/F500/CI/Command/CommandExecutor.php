<?php
/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

use F500\CI\Command\Process\ProcessFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class CommandExecutor
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
class CommandExecutor
{

    /**
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @param ProcessFactory $processFactory
     */
    public function __construct(ProcessFactory $processFactory)
    {
        $this->processFactory = $processFactory;
    }

    /**
     * @param Command         $command
     * @param LoggerInterface $logger
     * @return bool
     */
    public function execute(Command $command, LoggerInterface $logger)
    {
        $process = $this->processFactory->createProcess($command->getArgs(), $command->getCwd(), $command->getEnv());

        $logger->log(LogLevel::INFO, sprintf('[%s] Executing: %s', $command->getId(), $command->stringify(true)));
        $logger->log(LogLevel::DEBUG, sprintf('[%s] Raw command: %s', $command->getId(), $process->getCommandLine()));

        $command->clearResult();
        $process->run();
        $command->setResult($process->getExitCode(), $process->getOutput() . $process->getErrorOutput());

        $logger->log(
            $process->isSuccessful() ? LogLevel::INFO : LogLevel::ERROR,
            sprintf(
                '[%s] %s: %s',
                $command->getId(),
                $process->isSuccessful() ? 'Succeeded' : 'Failed',
                $command->stringify(true)
            ),
            array(
                'rc'  => $process->getExitCode(),
                'out' => $this->formatOutput($process->getOutput()),
                'err' => $this->formatOutput($process->getErrorOutput())
            )
        );

        return $process->isSuccessful();
    }

    /**
     * @param string $errors
     * @return string|array
     */
    protected function formatOutput($errors)
    {
        $errors = preg_split('/[\n\r]/', $errors);
        $errors = array_map('trim', $errors);
        $errors = array_filter($errors, 'strlen');
        $errors = array_values($errors);

        return $errors;
    }
}
