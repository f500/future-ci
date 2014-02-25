<?php

namespace F500\CI\Provider;

use F500\CI\Build\BuildFactory;
use F500\CI\Command\CommandFactory;
use F500\CI\Process\ProcessFactory;
use F500\CI\Run\Configurator;
use F500\CI\Run\Runner;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\TaskFactory;
use F500\CI\Wrapper\WrapperFactory;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ContinuousIntegrationServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     * @throws \RuntimeException
     */
    public function register(Application $app)
    {
        $app['f500ci.runner.suites_dir'] = '';
        $app['f500ci.runner.builds_dir'] = '';
        $app['f500ci.runner.class']      = 'F500\CI\Run\Runner';

        $app['f500ci.runner'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.runner.class'])) {
                    throw new \RuntimeException('"f500ci.runner.class" should be configured.');
                }
                if (empty($app['f500ci.runner.suites_dir'])) {
                    throw new \RuntimeException('"f500ci.runner.suites_dir" should be configured.');
                }
                if (empty($app['f500ci.runner.builds_dir'])) {
                    throw new \RuntimeException('"f500ci.runner.builds_dir" should be configured.');
                }

                $class    = $app['f500ci.runner.class'];
                $instance = new $class(
                    $app['f500ci.runner.suites_dir'],
                    $app['f500ci.runner.builds_dir'],
                    $app['f500ci.configurator'],
                    $app['dispatcher'],
                    $app['logger']
                );

                if (!$instance instanceof Runner) {
                    throw new \RuntimeException('"f500ci.runner" should be an instance of F500\CI\Run\Runner.');
                }

                return $instance;
            }
        );

        $app['f500ci.configurator.class'] = 'F500\CI\Run\Configurator';

        $app['f500ci.configurator'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.configurator.class'])) {
                    throw new \RuntimeException('"f500ci.configurator.class" should be configured.');
                }

                $class    = $app['f500ci.configurator.class'];
                $instance = new $class(
                    $app['f500ci.factory.build'],
                    $app['f500ci.factory.suite'],
                    $app['f500ci.factory.task'],
                    $app['f500ci.factory.wrapper']
                );

                if (!$instance instanceof Configurator) {
                    throw new \RuntimeException('"f500ci.factory.command" should be an instance of F500\CI\Run\Configurator.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.command.class']         = 'F500\CI\Command\CommandFactory';
        $app['f500ci.factory.command.command_class'] = 'F500\CI\Command\Command';

        $app['f500ci.factory.command'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.command.class'])) {
                    throw new \RuntimeException('"f500ci.factory.command.class" should be configured.');
                }
                if (empty($app['f500ci.factory.command.command_class'])) {
                    throw new \RuntimeException('"f500ci.factory.command.command_class" should be configured.');
                }

                $class    = $app['f500ci.factory.command.class'];
                $instance = new $class($app['f500ci.factory.command.command_class']);

                if (!$instance instanceof CommandFactory) {
                    throw new \RuntimeException('"f500ci.factory.command" should be an instance of F500\CI\Command\CommandFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.process.class']         = 'F500\CI\Process\ProcessFactory';
        $app['f500ci.factory.process.builder_class'] = 'Symfony\Component\Process\ProcessBuilder';

        $app['f500ci.factory.process'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.process.class'])) {
                    throw new \RuntimeException('"f500ci.factory.process.class" should be configured.');
                }
                if (empty($app['f500ci.factory.process.builder_class'])) {
                    throw new \RuntimeException('"f500ci.factory.process.builder_class" should be configured.');
                }

                $class    = $app['f500ci.factory.process.class'];
                $instance = new $class($app['f500ci.factory.process.builder_class']);

                if (!$instance instanceof ProcessFactory) {
                    throw new \RuntimeException('"f500ci.factory.process" should be an instance of F500\CI\Process\ProcessFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.build.class'] = 'F500\CI\Build\BuildFactory';

        $app['f500ci.factory.build'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.build.class'])) {
                    throw new \RuntimeException('"f500ci.factory.build.class" should be configured.');
                }

                $class    = $app['f500ci.factory.build.class'];
                $instance = new $class($app['filesystem']);

                if (!$instance instanceof BuildFactory) {
                    throw new \RuntimeException('"f500ci.factory.build" should be an instance of F500\CI\Build\BuildFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.suite.class'] = 'F500\CI\Suite\SuiteFactory';

        $app['f500ci.factory.suite'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.suite.class'])) {
                    throw new \RuntimeException('"f500ci.factory.suite.class" should be configured.');
                }

                $class    = $app['f500ci.factory.suite.class'];
                $instance = new $class();

                if (!$instance instanceof SuiteFactory) {
                    throw new \RuntimeException('"f500ci.factory.suite" should be an instance of F500\CI\Suite\SuiteFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.task.class'] = 'F500\CI\Task\TaskFactory';

        $app['f500ci.factory.task'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.task.class'])) {
                    throw new \RuntimeException('"f500ci.factory.task.class" should be configured.');
                }

                $class    = $app['f500ci.factory.task.class'];
                $instance = new $class($app['f500ci.factory.command'], $app['f500ci.factory.process']);

                if (!$instance instanceof TaskFactory) {
                    throw new \RuntimeException('"f500ci.factory.task" should be an instance of F500\CI\Task\TaskFactory.');
                }

                return $instance;
            }
        );

        $app['f500ci.factory.wrapper.class'] = 'F500\CI\Wrapper\WrapperFactory';

        $app['f500ci.factory.wrapper'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.factory.wrapper.class'])) {
                    throw new \RuntimeException('"f500ci.factory.wrapper.class" should be configured.');
                }

                $class    = $app['f500ci.factory.wrapper.class'];
                $instance = new $class();

                if (!$instance instanceof WrapperFactory) {
                    throw new \RuntimeException('"f500ci.factory.wrapper" should be an instance of F500\CI\Wrapper\WrapperFactory.');
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
