<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Event\BuildEvent;
use F500\CI\Event\Events;
use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use Psr\Log\LogLevel;

/**
 * Class StandardBuild
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class StandardBuild implements Build
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
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite)
    {
        $this->cn    = $cn;
        $this->suite = $suite;

        $suite->setActiveBuild($this);
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
     * @param Toolkit $toolkit
     * @return bool
     */
    public function initialize(Toolkit $toolkit)
    {
        return true;
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function run(Toolkit $toolkit)
    {
        $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" started.', $this->getCn()));
        $toolkit->getDispatcher()->dispatch(Events::BuildStarted, new BuildEvent($this));

        $result = $this->suite->run($toolkit);

        $toolkit->getDispatcher()->dispatch(Events::BuildFinished, new BuildEvent($this));
        $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Build "%s" finished.', $this->getCn()));

        return $result;
    }

    /**
     * @param Toolkit $toolkit
     * @return bool
     */
    public function cleanup(Toolkit $toolkit)
    {
        return true;
    }
}
