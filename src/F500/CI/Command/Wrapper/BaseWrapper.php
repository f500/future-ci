<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Wrapper;

use F500\CI\Suite\Suite;

/**
 * Class BaseWrapper
 *
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
     */
    public function __construct($cn)
    {
        $this->cn      = $cn;
        $this->options = array();
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
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
