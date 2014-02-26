<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Run;

use F500\CI\Build\Build;

/**
 * Class Runner
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Run
 */
class Runner
{

    /**
     * @var Configurator
     */
    protected $configurator;

    /**
     * @var Toolkit
     */
    protected $toolkit;

    /**
     * @param Configurator $configurator
     * @param Toolkit      $toolkit
     */
    public function __construct(Configurator $configurator, Toolkit $toolkit)
    {
        $this->configurator = $configurator;
        $this->toolkit      = $toolkit;
    }

    /**
     * @param string $filename
     * @return Build
     */
    public function setup($filename)
    {
        $config  = $this->configurator->loadConfig($filename);
        $suiteCn = substr($filename, 0, strrpos($filename, '.'));

        return $this->configurator->setup($suiteCn, $config);
    }

    /**
     * @param Build $build
     * @return bool
     */
    public function initialize(Build $build)
    {
        return $build->initialize($this->toolkit);
    }

    /**
     * @param Build $build
     * @return bool
     */
    public function run(Build $build)
    {
        return $build->run($this->toolkit);
    }

    /**
     * @param Build $build
     * @return bool
     */
    public function cleanup(Build $build)
    {
        return $build->cleanup($this->toolkit);
    }
}
