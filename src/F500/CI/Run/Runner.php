<?php

namespace F500\CI\Run;

use F500\CI\Build\Build;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Runner
{

    const DEFAULT_SUITE_CLASS = 'F500\CI\Suite\StandardSuite';

    const DEFAULT_BUILD_CLASS = 'F500\CI\Build\StandardBuild';

    /**
     * @var string
     */
    protected $suitesDir;

    /**
     * @var string
     */
    protected $buildsDir;

    /**
     * @var Configurator
     */
    protected $configurator;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param string                   $suitesDir
     * @param string                   $buildsDir
     * @param Configurator             $configurator
     * @param EventDispatcherInterface $dispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(
        $suitesDir,
        $buildsDir,
        Configurator $configurator,
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ) {
        $this->suitesDir = realpath($suitesDir);
        $this->buildsDir = realpath($buildsDir);

        $this->configurator = $configurator;
        $this->dispatcher   = $dispatcher;
        $this->logger       = $logger;
    }

    /**
     * @param string $filename
     * @return Build
     */
    public function setup($filename)
    {
        if (substr($filename, 0, 1) != '/') {
            $filename = $this->suitesDir . '/' . $filename;
        }

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
        return $build->initialize($this->dispatcher, $this->logger);
    }

    /**
     * @param Build $build
     * @return bool
     */
    public function run(Build $build)
    {
        return $build->run($this->dispatcher, $this->logger);
    }

    /**
     * @param Build $build
     * @return bool
     */
    public function cleanup(Build $build)
    {
        return $build->cleanup($this->dispatcher, $this->logger);
    }
}
