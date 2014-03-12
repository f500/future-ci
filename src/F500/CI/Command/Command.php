<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

/**
 * Class Command
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
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

    public function __construct()
    {
        $this->id   = base_convert(round(microtime(true) * 1000), 10, 36);
        $this->args = array();
        $this->env  = array();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $arg
     */
    public function addArg($arg)
    {
        $this->args[] = $arg;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param string $cwd
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

    /**
     * @return string
     */
    public function getCwd()
    {
        return $this->cwd;
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
     * @return array
     */
    public function getEnv()
    {
        return $this->env;
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
     * @param int    $resultCode
     * @param string $output
     */
    public function setResult($resultCode, $output)
    {
        $this->resultCode = $resultCode;
        $this->output     = $output;
    }

    public function clearResult()
    {
        $this->resultCode = null;
        $this->output     = null;
    }

    /**
     * @return int
     * @throws \RuntimeException
     */
    public function getResultCode()
    {
        if ($this->resultCode === null) {
            throw new \RuntimeException('Command has not been executed yet.');
        }

        return $this->resultCode;
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getOutput()
    {
        if ($this->output === null) {
            throw new \RuntimeException('Command has not been executed yet.');
        }

        return $this->output;
    }
}
