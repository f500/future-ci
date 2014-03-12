<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task;

use F500\CI\Command\Wrapper\Wrapper;

/**
 * Class BaseTask
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task
 */
abstract class BaseTask implements Task
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var ResultParser[]
     */
    protected $resultParsers;

    /**
     * @var \F500\CI\Command\Wrapper\Wrapper[]
     */
    protected $wrappers;

    /**
     * @var bool
     */
    protected $stopOnFailure;

    /**
     * @param string $cn
     */
    public function __construct($cn)
    {
        $this->cn            = $cn;
        $this->options       = array();
        $this->resultParsers = array();
        $this->wrappers      = array();
        $this->stopOnFailure = false;
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_replace_recursive(
            $this->getDefaultOptions(),
            $this->options
        );
    }

    /**
     * @param string       $cn
     * @param ResultParser $resultParser
     */
    public function addResultParser($cn, ResultParser $resultParser)
    {
        $this->resultParsers[$cn] = $resultParser;
    }

    /**
     * @return ResultParser[]
     */
    public function getResultParsers()
    {
        return $this->resultParsers;
    }

    /**
     * @param string                           $cn
     * @param \F500\CI\Command\Wrapper\Wrapper $wrapper
     */
    public function addWrapper($cn, Wrapper $wrapper)
    {
        $this->wrappers[$cn] = $wrapper;
    }

    /**
     * @return Wrapper[]
     */
    public function getWrappers()
    {
        return $this->wrappers;
    }

    /**
     * @return bool
     */
    public function stopOnFailure()
    {
        return $this->stopOnFailure;
    }

    /**
     * @param bool $stop
     */
    public function setStopOnFailure($stop)
    {
        $this->stopOnFailure = (bool)$stop;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array(
            'cwd' => '',
            'env' => array()
        );
    }
}
