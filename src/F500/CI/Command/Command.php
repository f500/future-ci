<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

use F500\CI\Process\ProcessFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class Command
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
class Command
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var string
     */
    protected $cwd;

    /**
     * @var array
     */
    protected $env;

    /**
     * @var int
     */
    protected $resultCode;

    /**
     * @var array
     */
    protected $output;

    /**
     * @var array
     */
    protected $errorOutput;

    /**
     * @var ProcessFactory
     */
    protected $processFactory;

    /**
     * @param ProcessFactory $processFactory
     */
    public function __construct(ProcessFactory $processFactory)
    {
        $this->id   = base_convert(round(microtime(true) * 1000), 10, 36);
        $this->args = array();
        $this->env  = array();

        $this->processFactory = $processFactory;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param string $arg
     */
    public function addArg($arg)
    {
        $this->args[] = $arg;
    }

    /**
     * @return string
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * @param string $cwd
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

    /**
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addEnv($name, $value)
    {
        $this->env[$name] = $value;
    }

    /**
     * @param bool $shorten
     * @return string
     */
    public function stringify($shorten = false)
    {
        $string = implode(' ', $this->getArgs());

        if ($shorten && strlen($string) > 80) {
            $string = substr($string, 0, 77) . '...';
        }

        return $string;
    }

    /**
     * @return int
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return array
     */
    public function getErrorOutput()
    {
        return $this->errorOutput;
    }

    /**
     * @param LoggerInterface $logger
     * @return bool
     */
    public function execute(LoggerInterface $logger)
    {
        $logger->log(LogLevel::INFO, sprintf('[%s] Executing: %s', $this->getId(), $this->stringify(true)));

        $process = $this->processFactory->create($this->getArgs(), $this->getCwd(), $this->getEnv());

        $logger->log(LogLevel::DEBUG, sprintf('[%s] Raw command: %s', $this->getId(), $process->getCommandLine()));

        $process->run();

        $this->resultCode  = $process->getExitCode();
        $this->output      = $this->formatOutput($process->getOutput());
        $this->errorOutput = $this->formatOutput($process->getErrorOutput());

        if ($process->isSuccessful()) {
            $logger->log(
                LogLevel::INFO,
                sprintf('[%s] Succeeded: %s', $this->getId(), $this->stringify(true)),
                array('rc' => $this->resultCode, 'out' => $this->output, 'err' => $this->errorOutput)
            );
        } else {
            $logger->log(
                LogLevel::ERROR,
                sprintf('[%s] Failed: %s', $this->getId(), $this->stringify(true)),
                array('rc' => $this->resultCode, 'out' => $this->output, 'err' => $this->errorOutput)
            );
        }

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
