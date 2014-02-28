<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Wrapper;

use F500\CI\Suite\Suite;

/**
 * Class BaseWrapper
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Wrapper
 */
abstract class BaseWrapper implements Wrapper
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite)
    {
        $this->cn      = $cn;
        $this->suite   = $suite;
        $this->options = array();

        $suite->addWrapper($cn, $this);
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
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
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }
}
