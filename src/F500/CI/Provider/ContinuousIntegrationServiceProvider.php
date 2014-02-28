<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Provider;

use F500\CI\Build\BuildFactory;
use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use F500\CI\Metadata\MetadataFactory;
use F500\CI\Process\ProcessFactory;
use F500\CI\Run\Configurator;
use F500\CI\Run\Runner;
use F500\CI\Run\Toolkit;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\TaskFactory;
use F500\CI\Wrapper\WrapperFactory;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ContinuousIntegrationServiceProvider
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Provider
 */
class ContinuousIntegrationServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     * @throws \RuntimeException
     */
    public function register(Application $app)
    {
        $app['f500ci.suites_dir'] = '';
        $app['f500ci.builds_dir'] = '';

        $app['f500ci.runner.class'] = 'F500\CI\Run\Runner';
        $app['f500ci.runner']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.runner.class'])) {
                    throw new \RuntimeException('"f500ci.runner.class" should be configured.');
                }
                if (empty($app['f500ci.suites_dir'])) {
                    throw new \RuntimeException('"f500ci.suites_dir" should be configured.');
                }

                $class    = $app['f500ci.runner.class'];
                $instance = new $class(
                    $app['f500ci.run_configurator'],
                    $app['f500ci.run_toolkit']
                );

                if (!$instance instanceof Runner) {
                    throw new \RuntimeException('"f500ci.runner" should be an instance of F500\CI\Run\Runner.');
                }

                return $instance;
            }
        );

        $app['f500ci.run_configurator.class'] = 'F500\CI\Run\Configurator';
        $app['f500ci.run_configurator']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.run_configurator.class'])) {
                    throw new \RuntimeException('"f500ci.run_configurator.class" should be configured.');
                }
                if (empty($app['f500ci.builds_dir'])) {
                    throw new \RuntimeException('"f500ci.builds_dir" should be configured.');
                }

                $class    = $app['f500ci.run_configurator.class'];
                $instance = new $class(
                    $app['f500ci.suites_dir'],
                    $app['f500ci.build_factory'],
                    $app['f500ci.suite_factory'],
                    $app['f500ci.task_factory'],
                    $app['f500ci.wrapper_factory'],
                    $app['f500ci.metadata_factory']
                );

                if (!$instance instanceof Configurator) {
                    throw new \RuntimeException('"f500ci.command_factory" should be an instance of F500\CI\Run\Configurator.');
                }

                return $instance;
            }
        );

        $app['f500ci.run_toolkit.class'] = 'F500\CI\Run\Toolkit';
        $app['f500ci.run_toolkit']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.run_toolkit.class'])) {
                    throw new \RuntimeException('"f500ci.run_toolkit.class" should be configured.');
                }
                if (empty($app['f500ci.builds_dir'])) {
                    throw new \RuntimeException('"f500ci.builds_dir" should be configured.');
                }

                $class    = $app['f500ci.run_toolkit.class'];
                $instance = new $class(
                    $app['f500ci.builds_dir'],
                    $app['f500ci.command_factory'],
                    $app['f500ci.command_executor'],
                    $app['dispatcher'],
                    $app['filesystem'],
                    $app['logger']
                );

                if (!$instance instanceof Toolkit) {
                    throw new \RuntimeException('"f500ci.run_toolkit" should be an instance of F500\CI\Run\Toolkit.');
                }

                return $instance;
            }
        );

        $app['f500ci.command_factory.class']                      = 'F500\CI\Command\CommandFactory';
        $app['f500ci.command_factory.command_class']              = 'F500\CI\Command\Command';
        $app['f500ci.command_factory.store_result_command_class'] = 'F500\CI\Command\StoreResultCommand';
        $app['f500ci.command_factory']                            = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.command_factory.class'])) {
                    throw new \RuntimeException('"f500ci.command_factory.class" should be configured.');
                }
                if (empty($app['f500ci.command_factory.command_class'])) {
                    throw new \RuntimeException('"f500ci.command_factory.command_class" should be configured.');
                }
                if (empty($app['f500ci.command_factory.store_result_command_class'])) {
                    throw new \RuntimeException('"f500ci.command_factory.store_result_command_class" should be configured.');
                }

                $class    = $app['f500ci.command_factory.class'];
                $instance = new $class(
                    $app['f500ci.command_factory.command_class'],
                    $app['f500ci.command_factory.store_result_command_class']
                );

                if (!$instance instanceof CommandFactory) {
                    throw new \RuntimeException('"f500ci.command_factory" should be an instance of F500\CI\Command\CommandFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.command_executor.class'] = 'F500\CI\Command\CommandExecutor';
        $app['f500ci.command_executor']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.command_executor.class'])) {
                    throw new \RuntimeException('"f500ci.command_executor.class" should be configured.');
                }

                $class    = $app['f500ci.command_executor.class'];
                $instance = new $class(
                    $app['f500ci.process_factory']
                );

                if (!$instance instanceof CommandExecutor) {
                    throw new \RuntimeException('"f500ci.command_executor" should be an instance of F500\CI\Command\CommandExecutor.');
                }

                return $instance;
            }
        );

        $app['f500ci.process_factory.class']         = 'F500\CI\Process\ProcessFactory';
        $app['f500ci.process_factory.builder_class'] = 'Symfony\Component\Process\ProcessBuilder';
        $app['f500ci.process_factory']               = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.process_factory.class'])) {
                    throw new \RuntimeException('"f500ci.process_factory.class" should be configured.');
                }
                if (empty($app['f500ci.process_factory.builder_class'])) {
                    throw new \RuntimeException('"f500ci.process_factory.builder_class" should be configured.');
                }

                $class    = $app['f500ci.process_factory.class'];
                $instance = new $class($app['f500ci.process_factory.builder_class']);

                if (!$instance instanceof ProcessFactory) {
                    throw new \RuntimeException('"f500ci.process_factory" should be an instance of F500\CI\Process\ProcessFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.build_factory.class'] = 'F500\CI\Build\BuildFactory';
        $app['f500ci.build_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.build_factory.class'])) {
                    throw new \RuntimeException('"f500ci.build_factory.class" should be configured.');
                }

                $class    = $app['f500ci.build_factory.class'];
                $instance = new $class();

                if (!$instance instanceof BuildFactory) {
                    throw new \RuntimeException('"f500ci.build_factory" should be an instance of F500\CI\Build\BuildFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.suite_factory.class'] = 'F500\CI\Suite\SuiteFactory';
        $app['f500ci.suite_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.suite_factory.class'])) {
                    throw new \RuntimeException('"f500ci.suite_factory.class" should be configured.');
                }

                $class    = $app['f500ci.suite_factory.class'];
                $instance = new $class();

                if (!$instance instanceof SuiteFactory) {
                    throw new \RuntimeException('"f500ci.suite_factory" should be an instance of F500\CI\Suite\SuiteFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.task_factory.class'] = 'F500\CI\Task\TaskFactory';
        $app['f500ci.task_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.task_factory.class'])) {
                    throw new \RuntimeException('"f500ci.task_factory.class" should be configured.');
                }

                $class    = $app['f500ci.task_factory.class'];
                $instance = new $class($app['f500ci.command_factory'], $app['f500ci.process_factory']);

                if (!$instance instanceof TaskFactory) {
                    throw new \RuntimeException('"f500ci.task_factory" should be an instance of F500\CI\Task\TaskFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.wrapper_factory.class'] = 'F500\CI\Wrapper\WrapperFactory';
        $app['f500ci.wrapper_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.wrapper_factory.class'])) {
                    throw new \RuntimeException('"f500ci.wrapper_factory.class" should be configured.');
                }

                $class    = $app['f500ci.wrapper_factory.class'];
                $instance = new $class();

                if (!$instance instanceof WrapperFactory) {
                    throw new \RuntimeException('"f500ci.wrapper_factory" should be an instance of F500\CI\Wrapper\WrapperFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.metadata_factory.class']                = 'F500\CI\Metadata\MetadataFactory';
        $app['f500ci.metadata_factory.build_metadata_class'] = 'F500\CI\Metadata\BuildMetadata';
        $app['f500ci.metadata_factory.suite_metadata_class'] = 'F500\CI\Metadata\SuiteMetadata';
        $app['f500ci.metadata_factory.task_metadata_class']  = 'F500\CI\Metadata\TaskMetadata';
        $app['f500ci.metadata_factory']                      = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.metadata_factory.class'])) {
                    throw new \RuntimeException('"f500ci.metadata_factory.class" should be configured.');
                }

                $class    = $app['f500ci.metadata_factory.class'];
                $instance = new $class(
                    $app['f500ci.metadata_factory.build_metadata_class'],
                    $app['f500ci.metadata_factory.suite_metadata_class'],
                    $app['f500ci.metadata_factory.task_metadata_class']
                );

                if (!$instance instanceof MetadataFactory) {
                    throw new \RuntimeException('"f500ci.metadata_factory" should be an instance of F500\CI\Metadata\MetadataFactory.');
                }

                return $instance;
            }
        );
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
