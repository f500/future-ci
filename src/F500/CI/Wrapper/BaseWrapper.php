<?php

namespace F500\CI\Wrapper;

abstract class BaseWrapper implements Wrapper
{

    /**
     * @var string
     */
    protected $cn;

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
