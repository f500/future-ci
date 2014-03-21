<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Provider;

use F500\CI\Build\BuildFactory;
use F500\CI\Command\CommandExecutor;
use F500\CI\Command\CommandFactory;
use F500\CI\Command\Process\ProcessFactory;
use F500\CI\Command\Wrapper\WrapperFactory;
use F500\CI\Runner\BuildRunner;
use F500\CI\Runner\Configurator;
use F500\CI\Runner\TaskRunner;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\ResultParserFactory;
use F500\CI\Task\TaskFactory;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ContinuousIntegrationServiceProvider
 *
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
        $app['f500ci.builds_dir'] = '';
        $app['f500ci.suites_dir'] = '';

        $app['f500ci.configurator.class'] = 'F500\CI\Runner\Configurator';
        $app['f500ci.configurator']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.configurator.class'])) {
                    throw new \RuntimeException('"f500ci.configurator.class" should be configured.');
                }
                if (empty($app['f500ci.builds_dir'])) {
                    throw new \RuntimeException('"f500ci.builds_dir" should be configured.');
                }

                $class    = $app['f500ci.configurator.class'];
                $instance = new $class(
                    $app['root_dir'],
                    $app['f500ci.builds_dir'],
                    $app['f500ci.suites_dir'],
                    $app['f500ci.build_factory'],
                    $app['f500ci.suite_factory'],
                    $app['f500ci.task_factory'],
                    $app['f500ci.result_parser_factory'],
                    $app['f500ci.wrapper_factory']
                );

                if (!$instance instanceof Configurator) {
                    throw new \RuntimeException(
                        '"f500ci.configurator" should be an instance of F500\CI\Runner\Configurator.'
                    );
                }

                return $instance;
            }
        );

        $app['f500ci.build_runner.class'] = 'F500\CI\Runner\BuildRunner';
        $app['f500ci.build_runner']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.build_runner.class'])) {
                    throw new \RuntimeException('"f500ci.build_runner.class" should be configured.');
                }

                $class    = $app['f500ci.build_runner.class'];
                $instance = new $class(
                    $app['f500ci.task_runner'],
                    $app['dispatcher'],
                    $app['filesystem'],
                    $app['logger']
                );

                if (!$instance instanceof BuildRunner) {
                    throw new \RuntimeException(
                        '"f500ci.build_runner" should be an instance of F500\CI\Runner\BuildRunner.'
                    );
                }

                return $instance;
            }
        );

        $app['f500ci.task_runner.class'] = 'F500\CI\Runner\TaskRunner';
        $app['f500ci.task_runner']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.task_runner.class'])) {
                    throw new \RuntimeException('"f500ci.task_runner.class" should be configured.');
                }

                $class    = $app['f500ci.task_runner.class'];
                $instance = new $class(
                    $app['f500ci.command_factory'],
                    $app['f500ci.command_executor'],
                    $app['logger']
                );

                if (!$instance instanceof TaskRunner) {
                    throw new \RuntimeException(
                        '"f500ci.task_runner" should be an instance of F500\CI\Runner\TaskRunner.'
                    );
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
                    throw new \RuntimeException(
                        '"f500ci.command_executor" should be an instance of F500\CI\Command\CommandExecutor.'
                    );
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
                    throw new \RuntimeException(
                        '"f500ci.build_factory" should be an instance of F500\CI\Build\BuildFactory.'
                    );
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
                    throw new \RuntimeException(
                        '"f500ci.command_factory.store_result_command_class" should be configured.'
                    );
                }

                $class    = $app['f500ci.command_factory.class'];
                $instance = new $class(
                    $app['f500ci.command_factory.command_class'],
                    $app['f500ci.command_factory.store_result_command_class']
                );

                if (!$instance instanceof CommandFactory) {
                    throw new \RuntimeException(
                        '"f500ci.command_factory" should be an instance of F500\CI\Command\CommandFactory.'
                    );
                }

                return $instance;
            }
        );

        $app['f500ci.process_factory.class']         = 'F500\CI\Command\Process\ProcessFactory';
        $app['f500ci.process_factory.process_class'] = 'Symfony\Component\Process\Process';
        $app['f500ci.process_factory']               = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.process_factory.class'])) {
                    throw new \RuntimeException('"f500ci.process_factory.class" should be configured.');
                }
                if (empty($app['f500ci.process_factory.process_class'])) {
                    throw new \RuntimeException('"f500ci.process_factory.process_class" should be configured.');
                }

                $class    = $app['f500ci.process_factory.class'];
                $instance = new $class(
                    $app['f500ci.process_factory.process_class']
                );

                if (!$instance instanceof ProcessFactory) {
                    throw new \RuntimeException(
                        '"f500ci.process_factory" should be an instance of F500\CI\Command\Process\ProcessFactory.'
                    );
                }

                return $instance;
            }
        );

        $app['f500ci.result_parser_factory.class'] = 'F500\CI\Task\ResultParserFactory';
        $app['f500ci.result_parser_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.result_parser_factory.class'])) {
                    throw new \RuntimeException('"f500ci.result_parser_factory.class" should be configured.');
                }

                $class    = $app['f500ci.result_parser_factory.class'];
                $instance = new $class();

                if (!$instance instanceof ResultParserFactory) {
                    throw new \RuntimeException(
                        '"f500ci.result_parser_factory" should be an instance of F500\CI\Task\ResultParserFactory.'
                    );
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
                    throw new \RuntimeException(
                        '"f500ci.suite_factory" should be an instance of F500\CI\Suite\SuiteFactory.'
                    );
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
                $instance = new $class();

                if (!$instance instanceof TaskFactory) {
                    throw new \RuntimeException(
                        '"f500ci.task_factory" should be an instance of F500\CI\Task\TaskFactory.'
                    );
                }

                return $instance;
            }
        );

        $app['f500ci.wrapper_factory.class'] = 'F500\CI\Command\Wrapper\WrapperFactory';
        $app['f500ci.wrapper_factory']       = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.wrapper_factory.class'])) {
                    throw new \RuntimeException('"f500ci.wrapper_factory.class" should be configured.');
                }

                $class    = $app['f500ci.wrapper_factory.class'];
                $instance = new $class();

                if (!$instance instanceof WrapperFactory) {
                    throw new \RuntimeException(
                        '"f500ci.wrapper_factory" should be an instance of F500\CI\Command\Wrapper\WrapperFactory.'
                    );
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
