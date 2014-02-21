<?php

namespace F500\CI\Run;

use F500\CI\Build\Build;
use F500\CI\Build\BuildFactory;
use F500\CI\Suite\Suite;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\Task;
use F500\CI\Task\TaskFactory;
use F500\CI\Wrapper\Wrapper;
use F500\CI\Wrapper\WrapperFactory;
use Igorw\Silex\JsonConfigDriver;
use Igorw\Silex\PhpConfigDriver;
use Igorw\Silex\TomlConfigDriver;
use Igorw\Silex\YamlConfigDriver;

class Configurator
{

    const DEFAULT_SUITE_CLASS = 'F500\CI\Suite\StandardSuite';

    const DEFAULT_BUILD_CLASS = 'F500\CI\Build\StandardBuild';

    /**
     * @var BuildFactory
     */
    protected $buildFactory;

    /**
     * @var SuiteFactory
     */
    protected $suiteFactory;

    /**
     * @var TaskFactory
     */
    protected $taskFactory;

    /**
     * @var WrapperFactory
     */
    protected $wrapperFactory;

    /**
     * @var Wrapper[]
     */
    protected $wrapperInstances;

    /**
     * @param BuildFactory   $buildFactory
     * @param SuiteFactory   $suiteFactory
     * @param TaskFactory    $taskFactory
     * @param WrapperFactory $wrapperFactory
     */
    public function __construct(
        BuildFactory $buildFactory,
        SuiteFactory $suiteFactory,
        TaskFactory $taskFactory,
        WrapperFactory $wrapperFactory
    ) {
        $this->buildFactory   = $buildFactory;
        $this->suiteFactory   = $suiteFactory;
        $this->taskFactory    = $taskFactory;
        $this->wrapperFactory = $wrapperFactory;

        $this->wrapperInstances = array();
    }

    /**
     * @param string $suiteCn
     * @param array  $config
     * @return Build
     */
    public function setup($suiteCn, array $config)
    {
        $suiteClass = $config['suite_class'];
        $buildClass = $config['build_class'];
        unset($config['suite_class'], $config['build_class']);

        $buildCn = $suiteCn . date('.Y.m.d.H.i.s');

        $suite = $this->suiteFactory->create($suiteClass, $suiteCn);
        $this->configureSuite($suite, $config);

        $build = $this->buildFactory->create($buildClass, $buildCn, $suite);
        $this->configureBuild($build, $config);

        return $build;
    }

    /**
     * @param string $filename
     * @return array
     * @throws \InvalidArgumentException
     */
    public function loadConfig($filename)
    {
        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf('Config file "%s" does not exist.', $filename));
        }

        if (substr($filename, -5) == '.json') {
            $driver = new JsonConfigDriver();
        } elseif (substr($filename, -4) == '.php') {
            $driver = new PhpConfigDriver();
        } elseif (substr($filename, -5) == '.toml') {
            $driver = new TomlConfigDriver();
        } elseif (substr($filename, -4) == '.yml' || substr($filename, -5) == '.yaml') {
            $driver = new YamlConfigDriver();
        } else {
            throw new \InvalidArgumentException(sprintf('Format of config file "%s" is not supported.', $filename));
        }

        $config = $driver->load($filename);

        if (empty($config['suite_class'])) {
            $config['suite_class'] = self::DEFAULT_SUITE_CLASS;
        }
        if (empty($config['build_class'])) {
            $config['build_class'] = self::DEFAULT_BUILD_CLASS;
        }

        return $config;
    }

    /**
     * @param Build $build
     * @param array $config
     */
    protected function configureBuild(Build $build, $config)
    {
        // TODO: write logic here
    }

    /**
     * @param Suite $suite
     * @param array $config
     * @throws \RuntimeException
     */
    protected function configureSuite(Suite $suite, array $config)
    {
        if (empty($config['name'])) {
            throw new \RuntimeException(sprintf('Suite "%s" has no name configured.', $suite->getCn()));
        }

        if (empty($config['tasks'])) {
            throw new \RuntimeException(sprintf('Suite "%s" has no tasks configured.', $suite->getCn()));
        } elseif (!is_array($config['tasks'])) {
            throw new \RuntimeException(sprintf('Tasks in suite "%s" should be an array.', $suite->getCn()));
        }

        if (empty($config['wrappers'])) {
            $config['wrappers'] = array();
        } elseif (!is_array($config['wrappers'])) {
            throw new \RuntimeException(sprintf('Wrappers in suite "%s" should be an array.', $suite->getCn()));
        }

        $suite->setName($config['name']);

        foreach ($config['wrappers'] as $wrapperCn => $wrapperConfig) {
            if (empty($wrapperConfig['class'])) {
                throw new \RuntimeException(sprintf(
                    'Wrapper "%s" in suite "%s" has no class configured.',
                    $wrapperCn,
                    $suite->getCn()
                ));
            }

            $wrapperClass = $wrapperConfig['class'];
            unset($wrapperConfig['class']);

            $wrapper = $this->wrapperFactory->create($wrapperClass, $wrapperCn);
            $this->configureWrapper($wrapper, $wrapperConfig);

            $this->wrapperInstances[$wrapperCn] = $wrapper;
        }

        foreach ($config['tasks'] as $taskCn => $taskConfig) {
            if (empty($taskConfig['class'])) {
                throw new \RuntimeException(sprintf(
                    'Task "%s" in suite "%s" has no class configured.',
                    $taskCn,
                    $suite->getCn()
                ));
            }

            $taskClass = $taskConfig['class'];
            unset($taskConfig['class']);

            $task = $this->taskFactory->create($taskClass, $taskCn);
            $this->configureTask($task, $taskConfig);

            $suite->addTask($taskCn, $task);
        }
    }

    /**
     * @param Task  $task
     * @param array $config
     * @throws \RuntimeException
     */
    protected function configureTask(Task $task, array $config)
    {
        if (empty($config['name'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no name configured.', $task->getCn()));
        }

        $task->setName($config['name']);
        unset($config['name']);

        if (!empty($config['wrappers'])) {
            if (!is_array($config['wrappers'])) {
                throw new \RuntimeException(sprintf('Wrappers in task "%s" should be an array.', $task->getCn()));
            }

            foreach ($config['wrappers'] as $wrapperCn) {
                if (!isset($this->wrapperInstances[$wrapperCn])) {
                    throw new \RuntimeException(sprintf('Wrapper "%s" in task "%s" is not configured.', $wrapperCn, $task->getCn()));
                }

                $task->addWrapper($wrapperCn, $this->wrapperInstances[$wrapperCn]);
            }

            unset($config['wrappers']);
        }

        $task->setOptions($config);
    }

    /**
     * @param Wrapper $wrapper
     * @param array   $config
     * @throws \RuntimeException
     */
    protected function configureWrapper(Wrapper $wrapper, array $config)
    {
        $wrapper->setOptions($config);
    }

}
