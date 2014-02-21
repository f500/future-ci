<?php

namespace F500\CI\Command;

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
}
