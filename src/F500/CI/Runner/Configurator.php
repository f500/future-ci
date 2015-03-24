<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\BuildFactory;
use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Command\Wrapper\WrapperFactory;
use F500\CI\Suite\Suite;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\Formatter;
use F500\CI\Task\FormatterFactory;
use F500\CI\Task\ResultParser;
use F500\CI\Task\ResultParserFactory;
use F500\CI\Task\Task;
use F500\CI\Task\TaskFactory;
use Igorw\Silex\JsonConfigDriver;
use Igorw\Silex\PhpConfigDriver;
use Igorw\Silex\TomlConfigDriver;
use Igorw\Silex\YamlConfigDriver;

/**
 * Class Configurator
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Runner
 */
class Configurator
{

    const DEFAULT_SUITE_CLASS = 'F500\CI\Suite\StandardSuite';

    const DEFAULT_BUILD_CLASS = 'F500\CI\Build\StandardBuild';

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $suitesDir;

    /**
     * @var string
     */
    protected $buildsDir;

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
     * @var ResultParserFactory
     */
    protected $resultParserFactory;

    /**
     * @var WrapperFactory
     */
    protected $wrapperFactory;

    /**
     * @var FormatterFactory
     */
    private $formatterFactory;

    /**
     * @param string              $rootDir
     * @param string              $buildsDir
     * @param string              $suitesDir
     * @param BuildFactory        $buildFactory
     * @param SuiteFactory        $suiteFactory
     * @param TaskFactory         $taskFactory
     * @param ResultParserFactory $resultParserFactory
     * @param FormatterFactory    $formatterFactory
     * @param WrapperFactory      $wrapperFactory
     */
    public function __construct(
        $rootDir,
        $buildsDir,
        $suitesDir,
        BuildFactory $buildFactory,
        SuiteFactory $suiteFactory,
        TaskFactory $taskFactory,
        ResultParserFactory $resultParserFactory,
        FormatterFactory $formatterFactory,
        WrapperFactory $wrapperFactory
    ) {
        $this->rootDir   = $rootDir;
        $this->buildsDir = realpath($buildsDir);
        $this->suitesDir = realpath($suitesDir);

        $this->buildFactory        = $buildFactory;
        $this->suiteFactory        = $suiteFactory;
        $this->taskFactory         = $taskFactory;
        $this->resultParserFactory = $resultParserFactory;
        $this->wrapperFactory      = $wrapperFactory;
        $this->formatterFactory    = $formatterFactory;
    }

    /**
     * @param string $filename
     * @param string $format
     * @param array  $parameters
     * @return array
     * @throws \InvalidArgumentException
     */
    public function loadConfig($filename, $format = null, array $parameters = array())
    {
        if (substr($filename, 0, 1) != '/') {
            $filename = $this->suitesDir . '/' . $filename;
        }

        if ($format) {
            $format = '.' . $format;
            if (substr($filename, strlen($format) * -1) != $format) {
                $filename .= $format;
            }
        }

        if (!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf('Config file "%s" does not exist.', $filename));
        }

        if (substr($filename, -5) == '.json') {
            $suiteCn = basename($filename, '.json');
            $driver  = new JsonConfigDriver();
        } elseif (substr($filename, -4) == '.php') {
            $suiteCn = basename($filename, '.php');
            $driver  = new PhpConfigDriver();
        } elseif (substr($filename, -5) == '.toml') {
            $suiteCn = basename($filename, '.toml');
            $driver  = new TomlConfigDriver();
        } elseif (substr($filename, -4) == '.yml') {
            $suiteCn = basename($filename, '.yml');
            $driver  = new YamlConfigDriver();
        } elseif (substr($filename, -5) == '.yaml') {
            $suiteCn = basename($filename, '.yaml');
            $driver  = new YamlConfigDriver();
        } else {
            throw new \InvalidArgumentException(sprintf('Format of config file "%s" is not supported.', $filename));
        }

        $config = $driver->load($filename);
        $config = $this->parseConfig($config, $parameters);

        $config['suite']['cn'] = isset($config['suite']['name'])
            ? strtolower(str_replace(' ', '_', $config['suite']['name']))
            : $suiteCn;

        if (empty($config['suite']['class'])) {
            $config['suite']['class'] = self::DEFAULT_SUITE_CLASS;
        }
        if (empty($config['build']['class'])) {
            $config['build']['class'] = self::DEFAULT_BUILD_CLASS;
        }

        return $config;
    }

    /**
     * @param string $class
     * @param string $cn
     * @param array  $config
     * @return Suite
     */
    public function createSuite($class, $cn, array $config)
    {
        $suite = $this->suiteFactory->createSuite($class, $cn, $config);
        $this->configureSuite($suite, $config['suite']);

        return $suite;
    }

    /**
     * @param string $class
     * @param Suite  $suite
     * @return Build
     */
    public function createBuild($class, Suite $suite)
    {
        $build = $this->buildFactory->createBuild($class, $suite, $this->buildsDir);

        return $build;
    }

    /**
     * @param Suite $suite
     * @param array $config
     * @throws \RuntimeException
     */
    protected function configureSuite(Suite $suite, array $config)
    {
        unset($config['suite']['cn'], $config['suite']['class'], $config['build']['class']);

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
                throw new \RuntimeException(sprintf('Wrapper "%s" has no class configured.', $wrapperCn));
            }

            $wrapperClass = $wrapperConfig['class'];
            unset($wrapperConfig['class']);

            $wrapper = $this->wrapperFactory->createWrapper($wrapperClass, $wrapperCn);
            $this->configureWrapper($wrapper, $wrapperConfig);
            $suite->addWrapper($wrapperCn, $wrapper);
        }

        foreach ($config['tasks'] as $taskCn => $taskConfig) {
            if (empty($taskConfig['class'])) {
                throw new \RuntimeException(sprintf('Task "%s" has no class configured.', $taskCn));
            }

            $taskClass = $taskConfig['class'];
            unset($taskConfig['class']);

            $task = $this->taskFactory->createTask($taskClass, $taskCn);
            $this->configureTask($task, $taskConfig, $suite->getWrappers());
            $suite->addTask($taskCn, $task);
        }
    }

    /**
     * @param Task      $task
     * @param array     $config
     * @param Wrapper[] $wrappers
     * @throws \RuntimeException
     */
    protected function configureTask(Task $task, array $config, array $wrappers)
    {
        if (empty($config['name'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no name configured.', $task->getCn()));
        }

        if (empty($config['parsers'])) {
            throw new \RuntimeException(sprintf('Task "%s" has no parsers configured.', $task->getCn()));
        } elseif (!is_array($config['parsers'])) {
            throw new \RuntimeException(sprintf('Parsers in task "%s" should be an array.', $task->getCn()));
        }

        $task->setName($config['name']);

        foreach ($config['parsers'] as $parserCn => $parserConfig) {
            if (empty($parserConfig['class'])) {
                throw new \RuntimeException(sprintf('Parser "%s" has no class configured.', $parserCn));
            }

            $parserClass = $parserConfig['class'];
            unset($parserConfig['class']);

            $parser = $this->resultParserFactory->createResultParser($parserClass, $parserCn);
            $this->configureResultParser($parser, $parserConfig);
            $task->addResultParser($parserCn, $parser);
        }

        $this->addFormattersToTask($task, $config);

        if (!empty($config['wrappers'])) {
            if (!is_array($config['wrappers'])) {
                throw new \RuntimeException(sprintf('Wrappers in task "%s" should be an array.', $task->getCn()));
            }

            foreach ($config['wrappers'] as $wrapperCn) {
                if (!isset($wrappers[$wrapperCn])) {
                    throw new \RuntimeException(
                        sprintf(
                            'Wrapper "%s" in task "%s" is not configured in suite.',
                            $wrapperCn,
                            $task->getCn()
                        )
                    );
                }

                $task->addWrapper($wrapperCn, $wrappers[$wrapperCn]);
            }
        }

        if (!empty($config['stop_on_failure'])) {
            $task->setStopOnFailure(true);
        }

        unset(
            $config['name'],
            $config['parsers'],
            $config['formatters'],
            $config['wrappers'],
            $config['stop_on_failure']
        );

        $task->setOptions($config);
    }

    /**
     * @param \F500\CI\Command\Wrapper\Wrapper $wrapper
     * @param array                            $config
     * @throws \RuntimeException
     */
    protected function configureWrapper(Wrapper $wrapper, array $config)
    {
        $wrapper->setOptions($config);
    }

    /**
     * @param ResultParser $resultParser
     * @param array        $config
     */
    protected function configureResultParser(ResultParser $resultParser, array $config)
    {
        $resultParser->setOptions($config);
    }

    /**
     * @param Formatter $formatter
     * @param array     $config
     */
    protected function configureFormatter(Formatter $formatter, array $config)
    {
        $formatter->setOptions($config);
    }

    /**
     * @param array $config
     * @param array $parameters
     * @return array
     */
    protected function parseConfig(array $config, array $parameters = array())
    {
        if (isset($config['parameters']) && is_array($config['parameters'])) {
            $parameters = array_replace_recursive($config['parameters'], $parameters);
        }

        $parameters = array_replace_recursive(get_defined_constants(), $parameters);

        if (empty($parameters['root_dir'])) {
            $parameters['root_dir'] = $this->rootDir;
        }

        array_walk_recursive(
            $config,
            function (&$value) use ($parameters) {
                if (is_string($value) && preg_match_all('/%(.+?)%/', $value, $m)) {
                    foreach ($m[1] as $i => $var) {
                        if (array_key_exists($var, $parameters)) {
                            if ($value === $m[0][$i]) {
                                $value = $parameters[$var];
                            } else {
                                $value = str_replace($m[0][$i], $parameters[$var], $value);
                            }
                        }
                    }
                }
            }
        );

        unset($config['parameters']);

        return $config;
    }

    /**
     * Retrieves the formatters from the 'formatters' element in the config, retrieves them from the formatter factory
     * and registers them to the provided task.
     *
     * @param Task             $task
     * @param string[]|array[] $config
     *
     * @return void
     */
    protected function addFormattersToTask(Task $task, array $config)
    {
        if (empty($config['formatters'])) {
            return;
        }

        if (!is_array($config['formatters'])) {
            throw new \RuntimeException(sprintf('Formatters in task "%s" should be an array.', $task->getCn()));
        }

        foreach ($config['formatters'] as $formatterCn => $formatterConfig) {
            if (empty($formatterConfig['class'])) {
                throw new \RuntimeException(sprintf('Formatter "%s" has no class configured.', $formatterCn));
            }

            $formatterClass = $formatterConfig['class'];
            unset($formatterConfig['class']);

            $formatter = $this->formatterFactory->createFormatter($formatterClass, $formatterCn);
            $this->configureFormatter($formatter, $formatterConfig);
            $task->addFormatter($formatterCn, $formatter);
        }
    }
}
